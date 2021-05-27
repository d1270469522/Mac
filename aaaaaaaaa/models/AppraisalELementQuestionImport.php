<?php
/**
 * 要素题导入
 * author：天尽头流浪
 */
namespace App\Models;

use PHPExcel_IOFactory;
use App\Common\BaseModel;
use Key\Database\Mongodb;
use Key\Exception\AppException;
use Psr\Log\InvalidArgumentException;

class AppraisalElementQuestionImport extends BaseModel
{
    const INVALID_CODE_UNDEFINED = 1;
    const INVALID_CODE_REQUIRED = 2;

    // 表头
    protected static $headers = [
        'type' => '题型',
        'name' => '题目名称',
        'desc' => '题目描述',
        'options_a' => '选项A',
        'options_b' => '选项B',
        'options_c' => '选项C',
        'options_d' => '选项D',
        'options_e' => '选项E',
        'options_f' => '选项F',
        'options_g' => '选项G',
        'options_h' => '选项H',
        'options_i' => '选项I',
        'options_j' => '选项J',
    ];

    /** @var \PHPExcel */
    protected $excel;

    /** @var \PHPExcel_Reader_IReader */
    protected $excelReader;

    /** @var \App\Models\EnterpriseElement */
    protected $enterpriseElementModel;

    // 文件信息
    protected $file;

    // array [行数, 列数, 表头]
    protected $info;

    // 统计导入成功的记录
    protected $validRows = [];

    // 统计导入失败的记录
    protected $invalidRows = [];

    // 要素表ID
    protected $element_id = 0;

    /**
     * 设置要素ID
     */
    public function setElementId($id)
    {
        $this->element_id = $id;
    }

    /**
     * 初始化
     */
    protected function init($file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException('Invalid file: ' . $file);
        }
        $this->file = $file;
        $this->enterpriseElementModel = new EnterpriseElement($this->app);
        $this->excelReader = static::getInfo($file, $info, $excel);
        $this->info = $info;
        $this->excel = $excel;
    }

    /**
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

        if (count(ArrayGet($this->info, 'header')) != count(static::$headers)) {
            throw new AppException('表头不符,请重新下载模板');
        }

        $highestRow = ArrayGet($this->info, 'highestRow', 0);
        $highestColumn = ArrayGet($this->info, 'highestColumn', 0);

        // 设置活动页索引
        $activeSheet = $this->excel->setActiveSheetIndex(0);

        for ($i = 3; $i <= $highestRow; $i++) {
            $rowData = [];
            $is_data = false;
            for ($col = 0; $col < $highestColumn; $col++) {
                // getCellByColumnAndRow：使用数值单元格坐标获取特定坐标的单元格
                // 从第三行开始，获取每一行数据
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
     * 要素题导入逻辑，入库操作
     * @param $rowData 第几行的数据
     * @param $row  第几行的行号
     * @return bool
     */
    protected function validateRow($rowData, $row)
    {
        if (count($rowData) == count(static::$headers)) {

            $newRow = [];
            $is_ok = true;
            // 获取表头的索引，组成新的数组
            $headers_keys = array_keys(static::$headers);

            foreach ($rowData as $index => $value) {
                $value = trim($value);
                switch ($column_key = $headers_keys[$index]) {
                    // 题型
                    case 'type':
                        if ($value) {
                            $newRow['type'] = $this->getQuestionType($value);
                        } else {
                            $this->addInvalidRow($row, $index, static::$headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '题型必填');
                            $is_ok = false;
                        }
                        break;
                    // 题目
                    case 'name':
                        if (strlen($value) == 0) {
                            $this->addInvalidRow($row, $index, static::$headers[$column_key], $value, self::INVALID_CODE_REQUIRED, '题目名称必填');
                            $is_ok = false;
                            break;
                        }
                        $newRow['name'] = $value;
                        break;
                    // 描述
                    case 'desc':
                        $newRow['desc'] = $value;
                        break;
                    // 选项A
                    case 'options_a':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(1, $value, 'A');
                        }
                        break;
                    // 选项B
                    case 'options_b':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(2, $value, 'B');
                        }
                        break;
                    // 选项C
                    case 'options_c':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(3, $value, 'C');
                        }
                        break;
                    // 选项D
                    case 'options_d':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(4, $value, 'D');
                        }
                        break;
                    // 选项E
                    case 'options_e':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(5, $value, 'E');
                        }
                        break;
                    // 选项F
                    case 'options_f':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(6, $value, 'F');
                        }
                        break;
                    // 选项G
                    case 'options_g':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(7, $value, 'G');
                        }
                        break;
                    // 选项H
                    case 'options_h':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(8, $value, 'H');
                        }
                        break;
                    // 选项I
                    case 'options_i':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(9, $value, 'I');
                        }
                        break;
                    // 选项J
                    case 'options_j':
                        if ($value) {
                            $newRow['options'][] = $this->handleOptions(10, $value, 'J',);
                        }
                        break;

                }
                if ($is_ok == false) {
                    return false;
                }
            }

            if ($is_ok && $newRow) {
                $newRow['em_id'] = $this->element_id;
                $newRow['eid'] = $this->eid;
                $newRow['status'] = 1;
                $newRow['enable'] = 1;
                $newRow['created'] = Mongodb::getMongoDate();
                $newRow['updated'] = Mongodb::getMongoDate();
                $newRow['order'] = 0;

                $importResult = $this->enterpriseElementModel->createElementQuestion($newRow);
                if ($importResult) {
                    $this->enterpriseElementModel->countQuestionNum($this->element_id);
                    $this->addValidRow($row, '');
                }

            } else {
                error_log('Invalid row');
            }

        } else {
            error_log('Invalid template headers');
        }

        return false;
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
            'column_friendly_name' => ArrayGet(static::$headers, $columnName),
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
     * 将题型名称转换为对应ID
     * @param $type_name
     * @return int
     */
    public function getQuestionType($type_name)
    {
        if (!$type_name) {
            return 0;
        }

        switch ($type_name) {
            case '单选打分题':
                return 1;
            case '多选打分题':
                return 2;
            case '文本题':
                return 3;
        }

        return 0;
    }

    /**
     * 选项格式化
     */
    public function handleOptions($id, $value, $option)
    {
        if (! $option || ! $value) {
            return [];
        }

        $value_arr = explode('/', $value);

        $value_content = $value_arr[0] ?: '';
        $value_score = $value_arr[1] ?: 0;

        return [
            'id' => $id,
            'content' => $value_content,
            'name' => $option,
            'score' => (int)$value_score,
        ];
    }

}
