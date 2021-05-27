<?php
/**
 * 企业测评-要素题导入
 * author dpf
 */

namespace App\Models;

use PHPExcel_IOFactory;
use App\Common\BaseModel;
use App\Common\Constants;
use Key\Exception\AppException;
use Psr\Log\InvalidArgumentException;

class AppraisalElementRelationsImport extends BaseModel
{
    const INVALID_CODE_UNDEFINED = 1;
    const INVALID_CODE_REQUIRED = 2;

    /** @var \PHPExcel */
    protected $excel;

    /** @var \PHPExcel_Reader_IReader */
    protected $excelReader;

    /** @var \App\Models\Employee */
    protected $employeeModel;

    /** @var \App\Models\EnterpriseAppraisal */
    protected $EnterpriseAppraisalModel;

    // 表头
    protected $headers = [];

    // 文件信息
    protected $file;

    // array [行数, 列数, 表头]
    protected $info;

    // 统计导入成功的记录
    protected $validRows = [];

    // 统计导入失败的记录
    protected $invalidRows = [];

    // 评估表ID
    protected $appraisal_id = 0;

    // 1、外部员工；2、内部员工
    protected $type = 0;

    // 评估关系权重
    protected $relations_weight = [];

    /**
     * 初始化
     */
    protected function init($file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException('Invalid file: ' . $file);
        }

        if ($this->type == 1) { // 内部员工表头
            $this->headers = [
                'be_assessed_people_no' => '被评估人工号',
                'be_assessed_people_name' => '被评估人姓名',
                'be_assessed_people_name' => '被评估人姓名',
                'relation_name' => '评估关系',
                'assessed_people_no' => '评估人工号',
                'assessed_people_name' => '评估人姓名',
                'assessed_people_email' => '评估人邮箱',
            ];
        } else if ($this->type == 2){ // 外部员工表头
            $this->headers = [
                'be_assessed_people_no' => '被评估人工号',
                'be_assessed_people_name' => '被评估人姓名',
                'be_assessed_people_email' => '被评估人邮箱',
                'be_assessed_people_position_name' => '被评估人岗位',
                'be_assessed_people_department_name' => '被评估人部门',
                'relation_name' => '评估关系',
                'assessed_people_no' => '评估人工号',
                'assessed_people_name' => '评估人姓名',
                'assessed_people_email' => '评估人邮箱',
            ];
        } else {
            throw new InvalidArgumentException('Invalid input: TYPE');
        }

        $this->relations_weight = $this->setRelationWeight();
        $this->file = $file;
        $this->EnterpriseAppraisalModel = new EnterpriseAppraisal($this->app);
        $this->employeeModel = new Employee($this->app);
        $this->excelReader = static::getInfo($file, $info, $excel);
        $this->info = $info;
        $this->excel = $excel;
    }

    /**
     * 设置评估主表ID
     */
    public function setAppraisalId($appraisal_id)
    {
        $this->appraisal_id = $appraisal_id;
    }

    /**
     * 设置 type 外部1，内部2
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get some information of the excel file.
     * 获取excel文件的信息
     */
    protected static function getInfo($file, &$info, &$excel)
    {
        $path_info = pathinfo($file);
        if (isset($path_info['extension'])) {
            switch (strtolower($path_info['extension'])) {
                case 'xls':
                    $excelType = 'Excel5';
                    break;
                case 'xlsx':
                default:
                    $excelType = 'Excel2007';
                    break;
            }
        } else {
            $excelType = 'Excel2007';
        }

        // 根据上传文件的扩展，实例化类
        $excelReader = PHPExcel_IOFactory::createReader($excelType);

        // 读取并加载 上传文件的内容
        $excel = $excelReader->load($file);

        // 设置活动页索引
        $sheet = $excel->setActiveSheetIndex(0);

        // columnIndexFromString：字符串中的列索引
        // getHighestColumn：得到最高的工作表列
        $highestColumn = \PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());

        $header = array();
        for ($i = 0; $i < $highestColumn; $i++) {
            // getCellByColumnAndRow：使用数值单元格坐标获取特定坐标的单元格
            // 获取第二行的数据：表头
            $header[] = (string)$sheet->getCellByColumnAndRow($i, 2)->getValue();
        }

        $info = array(
            // 获得最高的工作表行
            'highestRow' => $sheet->getHighestRow(),
            'highestColumn' => $highestColumn,
            'header' => $header
        );

        return $excelReader;
    }

    /**
     * 执行导入，插入数据库
     */
    public function exec($file)
    {
        $this->init($file);

        if (count(ArrayGet($this->info, 'header')) != count($this->headers)) {
            throw new AppException($this->info);
        }

        $highestRow = ArrayGet($this->info, 'highestRow', 0);
        $highestColumn = ArrayGet($this->info, 'highestColumn', 0);

        // 设置活动页索引
        $activeSheet = $this->excel->setActiveSheetIndex(0);

        for ($i = 3; $i <= $highestRow; $i++) {

            // 从第三行开始，获取每一行数据
            $rowData = [];
            $is_data = false;

            for ($col = 0; $col < $highestColumn; $col++) {
                // getCellByColumnAndRow：使用数值单元格坐标获取特定坐标的单元格
                $rowData[] = (string)$activeSheet->getCellByColumnAndRow($col, $i);
            }

            // 对每一行的数据，每一个字段进行过滤空格
            foreach ($rowData as $val) {
                $val = trim($val);
                if ($val) {
                    $is_data = true;
                    break;
                }
            }
            if ($rowData && $is_data) {
                if (! $this->validateRow($rowData, $i)) {
                    error_log('[exec] Invalid row: ' . $i);
                }
            } else {
                error_log('[exec] Empty row - ' . $i);
            }
        }
    }

    /**
     * 员工导入逻辑，入库操作
     * @param $rowData 第几行的数据
     * @param $row  第几行的行号
     * @return bool
     */
    protected function validateRow($rowData, $row)
    {
        if (count($rowData) == count($this->headers)) {
            // 格式化数据
            if ($this->type == 1) {
                $newRow = $this->dataFormatV1($rowData, $row);
            } else if ($this->type == 2) {
                $newRow = $this->dataFormatV2($rowData, $row);
            } else {
                error_log('Invalid row');
            }
            if ($newRow) {
                // 对应关系入库
                $importResult = $this->EnterpriseAppraisalModel->createRelationsInfo($this->appraisal_id, $newRow);
                if ($importResult) {
                    // 入库成功，添加到成功记录
                    $this->addValidRow($row, '');
                }
            } else {
                error_log('Invalid row');
            }
        } else {
            error_log('Invalid template headers');
        }
    }

    /**
     * 内部员工 数据格式化
     * @param $rowData 第几行的数据
     * @param $row  第几行的行号
     */
    protected function dataFormatV1($rowData, $row)
    {
        $is_ok = true;
        foreach ($rowData as $index => $value) {
            $headers_keys = array_keys($this->headers);
            switch ($column_key = $headers_keys[$index]) {
                case 'be_assessed_people_no':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '被评估人编号必填');
                        $is_ok = false;
                        break;
                    }
                    $employee = $this->employeeModel->getByNo($value);
                    if (! $employee) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '系统中不存在此编号');
                        $is_ok = false;
                        break;
                    }
                    $newRow['be_assessed_people_no'] = $value;
                    $newRow['be_assessed_people_name'] = $employee['display'];
                    $newRow['be_assessed_people_email'] = $employee['email'];
                    $newRow['be_assessed_people_department_id'] = $employee['department_id'];
                    $newRow['be_assessed_people_department_name'] = $employee['department_name'];
                    $newRow['be_assessed_people_position_id'] = $employee['position_id'];
                    $newRow['be_assessed_people_position_name'] = $employee['position'];
                    break;
                case 'relation_name':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估关系必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['relation_id'] = $this->getRelationName($value);
                    $newRow['relation_weight'] = $this->getRelationWeight($value);
                    $newRow['relation_name'] = $value;
                    break;
                case 'assessed_people_no':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估人姓名必填');
                        $is_ok = false;
                        break;
                    }
                    $employee = $this->employeeModel->getByNo($value);
                    if (! $employee) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '系统中不存在此编号');
                        $is_ok = false;
                        break;
                    }
                    $newRow['assessed_people_no'] = $value;
                    $newRow['assessed_people_name'] = $employee['display'];
                    $newRow['assessed_people_email'] = $employee['email'];
                    break;
                case 'be_assessed_people_name':
                case 'be_assessed_people_email':
                case 'assessed_people_name':
                case 'assessed_people_email':
                    break;
                default :
                    $is_ok = false;
                    $this->addInvalidRow($row, $index, '', $value, self::INVALID_CODE_REQUIRED, '');
                    break;
            }
        }
        if ($is_ok) {
            return $newRow;
        }
    }

    /**
     * 外部员工 数据格式化
     * @param $rowData 第几行的数据
     * @param $row  第几行的行号
     */
    protected function dataFormatV2($rowData, $row)
    {
        $newRow = [];
        $is_ok = true;
        // 获取表头的索引，组成新的数组
        $headers_keys = array_keys($this->headers);

        foreach ($rowData as $index => $value) {
            $value = trim($value);
            switch ($column_key = $headers_keys[$index]) {
                case 'be_assessed_people_no':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '被评估人编号必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['be_assessed_people_no'] = $value;
                    break;
                case 'be_assessed_people_name':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '被评估人姓名必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['be_assessed_people_name'] = $value;
                    break;
                case 'be_assessed_people_email':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '被评估人邮箱必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['be_assessed_people_email'] = $value;
                    break;
                case 'be_assessed_people_department_name':
                    $newRow['be_assessed_people_department_name'] = $value;
                    break;
                case 'be_assessed_people_position_name':
                    $newRow['be_assessed_people_position_name'] = $value;
                    break;
                case 'relation_name':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估关系必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['relation_id'] = $this->getRelationName($value);
                    $newRow['relation_weight'] = $this->getRelationWeight($value);
                    $newRow['relation_name'] = $value;
                    break;
                case 'assessed_people_no':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估人编号必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['assessed_people_no'] = $value;
                    break;
                case 'assessed_people_name':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估人姓名必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['assessed_people_name'] = $value;
                    break;
                case 'assessed_people_email':
                    if (strlen($value) == 0) {
                        // 添加错误数据
                        $this->addInvalidRow($row, $index, $this->headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '评估人邮箱必填');
                        $is_ok = false;
                        break;
                    }
                    $newRow['assessed_people_email'] = $value;
                    break;
                default :
                    $is_ok = false;
                    $this->addInvalidRow($row, $index, '', $value, self::INVALID_CODE_REQUIRED, '');
                    break;
            }
        }
        if ($is_ok) {
            return $newRow;
        } else {
            return false;
        }
    }

    /**
     * 添加错误数据
     */
    protected function addInvalidRow($rowIndex, $column, $columnName, $value, $invalidCode = self::INVALID_CODE_UNDEFINED, $msg = '')
    {
        $this->invalidRows[] = [
            'row' => $rowIndex,
            'column' => $column,
            'column_name' => $columnName,
            'column_friendly_name' => ArrayGet($this->headers, $columnName),
            'value' => $value,
            'code' => $invalidCode,
            'msg' => $msg
        ];
    }

    /**
     * 添加正确数据
     */
    protected function addValidRow($rowIndex, $rowData)
    {
        $this->validRows[] = [
            'row' => $rowIndex,
            'data' => $rowData
        ];
    }

    /**
     * 获取错误行数
     */
    public function getInvalidRows()
    {
        return $this->invalidRows;
    }

    /**
     * 获取正确行数
     */
    public function getValidRows()
    {
        return $this->validRows;
    }

    /**
     * 设置评估关系权重
     */
    public function setRelationWeight()
    {
        $db = $this->getMongoMasterConnection();

        $cond = [
            'id' => $this->appraisal_id,
            'aid' => $this->aid,
            'status' => 1,
        ];

        $appraisal_res = $db->fetchRow(Constants::COLL_ENTERPRISE_APPRAISAL, $cond);

        if (! $appraisal_res) {
            $relations_weight = [
                [
                    'role_id' => 1,
                    'role_name' => '上级',
                    'weight' => 100,
                ],
                [
                    'role_id' => 2,
                    'role_name' => '同级',
                    'weight' => 100,
                ],
                [
                    'role_id' => 3,
                    'role_name' => '下级',
                    'weight' => 100,
                ],
                [
                    'role_id' => 4,
                    'role_name' => '客户',
                    'weight' => 100,
                ],
                [
                    'role_id' => 5,
                    'role_name' => '自己',
                    'weight' => 100,
                ]
            ];
        } else {
            $relations_weight = $appraisal_res['relations'];
        }

        return $relations_weight;
    }

    /**
     * 将题型名称转换为对应ID
     * @param $type_name
     * @return int
     */
    public function getRelationName($relation_name)
    {
        if (!$relation_name)
            return 0;

        switch ($relation_name) {
            case '上级':
                return 1;
            case '同级':
                return 2;
            case '下级':
                return 3;
            case '客户':
                return 4;
            case '自己':
                return 5;
        }

        return 0;
    }

    /**
     * 将题型名称转换为对应ID
     * @param $type_name
     * @return int
     */
    public function getRelationWeight($relation_name)
    {
        if (!$relation_name)
            return 0;

        foreach ($this->relations_weight as $key => $value) {
            if ($relation_name == $value['role_name']) {
                return $value['weight'];
            }
        }

        return 0;
    }
}
