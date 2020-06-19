


/************************************************************\
 **                                                        **
 **                       还款情况                          **
 **                                                        **
\************************************************************/
SELECT
    COALESCE(tmp_table.应还日期, '总共') AS '应还日期',
    IFNULL(tmp_table.是否续贷, '合计') AS '首续贷',
    COUNT(tmp_table.订单ID) AS '应还单量',
    SUM(tmp_table.应还本息) AS '应还本息',
    SUM(tmp_table.应还逾期费) AS '应还逾期费',
    COUNT(tmp_table.实还笔数) AS '实还笔数',
    SUM(tmp_table.实还本息) AS '实还本息',
    SUM(tmp_table.实还逾期费) AS '实还逾期费',
    COUNT(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 0) THEN 1 ELSE NUll END) AS 'PD0触发单量',
    SUM(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 0) THEN tmp_table.应还本息 ELSE 0 END) AS 'PD0触发金额',
    COUNT(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 0) THEN tmp_table.订单ID ELSE NUll END) / COUNT( tmp_table.订单ID ) AS 'PD0触发率%',
    COUNT(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 2) THEN 1 ELSE NUll END) AS 'PD2触发单量',
    SUM(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 2) THEN tmp_table.应还本息 ELSE 0 END) AS 'PD2触发金额',
    COUNT(CASE WHEN (tmp_table.是否触发 IN ('逾期未还', '逾期还款') AND tmp_table.逾期天数 > 2) THEN tmp_table.订单ID ELSE NUll END) / COUNT(tmp_table.订单ID) AS 'PD2触发率%'
FROM
(
    SELECT
        h.id AS '订单ID',
        (h.repayment_amount - h.overdue_amount) AS '应还本息',
        IF(i.user_type = '1', '首贷', '续贷') AS '是否续贷',
        SUBSTR( h.loan_deadline, 1, 10)  AS '应还日期',
        h.overdue_amount AS '应还逾期费',
        IF(h.order_status IN (12), 1, NULL) AS '实还笔数',
        IF(h.order_status IN (12), (h.repayment_amount - h.overdue_amount), 0) AS '实还本息',
        IF(h.order_status IN (12), h.overdue_amount, 0) AS '实还逾期费',
        CASE WHEN h.order_status IN (11, 13) AND SUBSTR( h.loan_deadline, 1, 10) < CURDATE() THEN '逾期未还'
            WHEN h.order_status IN (12) AND SUBSTR(h.loan_repayment_date, 1, 10) > SUBSTR(h.loan_deadline, 1, 10) THEN '逾期还款'
            WHEN h.order_status IN (12) AND SUBSTR(h.loan_repayment_date, 1, 10) <= SUBSTR(h.loan_deadline, 1, 10) THEN '按时还款'
            WHEN h.order_status IN (14) THEN '放款成功'
            WHEN h.order_status IN (25) THEN '当天到期'
            ELSE NULL
        END AS '是否触发',
        CASE WHEN h.order_status IN (11, 13) AND SUBSTR( h.loan_deadline, 1, 10) < CURDATE() THEN DATEDIFF(CURDATE(), SUBSTR(h.loan_deadline, 1, 10))
            WHEN h.order_status IN (12) AND SUBSTR(h.loan_repayment_date, 1, 10) > SUBSTR(h.loan_deadline, 1, 10) THEN DATEDIFF(SUBSTR(h.loan_repayment_date, 1, 10), SUBSTR(h.loan_deadline, 1, 10))
            WHEN h.order_status IN (12) AND SUBSTR(h.loan_repayment_date, 1, 10) <= SUBSTR(h.loan_deadline, 1, 10) THEN 0
            WHEN h.order_status IN (14) THEN 0
            WHEN h.order_status IN (25) THEN 0
            ELSE NUll
        END AS '逾期天数'
    FROM tb_user_access c
    LEFT JOIN tb_loan_order h ON h.uid=c.user_id
    LEFT JOIN tb_order_product_info i ON i.order_id = h.id
    WHERE h.order_status IN (14, 11, 12, 13, 25)
    AND SUBSTR( h.loan_deadline,  1, 10) >= '2019-05-20'
    AND SUBSTR( h.loan_deadline,  1, 10) <= '2019-05-30'
    ORDER BY h.id, h.order_time ASC
) AS tmp_table
GROUP BY tmp_table.应还日期 DESC, tmp_table.是否续贷 DESC
WITH ROLLUP





/************************************************************\
 **                                                        **
 **                       印尼新风控                         **
 **                                                        **
 **                     统计：电审记录                       **
 **                                                        **
\************************************************************/
SELECT
    a.order_number,
    b.flag,
    SUBSTRING(
        REPLACE(work, '}' , ','),
        LOCATE('dian_reason1":"', REPLACE(work, '}' , ',') ) + CHAR_LENGTH('dian_reason1":"'),
        LOCATE('",' ,REPLACE(work, '}' , ','), LOCATE('dian_reason1":"', REPLACE(work, '}' , ',')) + CHAR_LENGTH('dian_reason1":"'))-(LOCATE('dian_reason1":"' ,REPLACE(work,'}' ,',')) + CHAR_LENGTH('dian_reason1":"'))
    ) AS 'dian_reason1',

    SUBSTRING(
        REPLACE(peason, '}' , ','),
        LOCATE('dian_reason2":"', REPLACE(peason, '}' , ',') ) + CHAR_LENGTH('dian_reason2":"'),
        LOCATE('",' ,REPLACE(peason, '}' , ','), LOCATE('dian_reason2":"', REPLACE(peason, '}' , ',')) + CHAR_LENGTH('dian_reason2":"'))-(LOCATE('dian_reason2":"' ,REPLACE(peason,'}' ,',')) + CHAR_LENGTH('dian_reason2":"'))
    ) AS 'dian_reason2',
    c.reason_code,
    c.updated_at
FROM `tb_loan_order` AS a
LEFT JOIN `tb_order_user_basic_info` AS b   ON a.basic_id = b.id
LEFT JOIN `tb_loan_trial_phone_record` AS c ON a.id = c.order_id
WHERE b.flag = 2
AND c.reason_code < 100
AND SUBSTR(a.order_time, 1,10) > '2019-06-27'
AND SUBSTR(c.updated_at, 1,10) < '2019-07-10'





/************************************************************\
 **                                                        **
 **                        国内风控                         **
 **                                                        **
 **                    统计【小镜子】调用记录                  **
 **                                                        **
\************************************************************/
SELECT
    CASE
        WHEN partner_id = 1701171101015  THEN '小镜子'
        WHEN partner_id = 1701171101019  THEN '随身宝'
        WHEN partner_id = 1701171101020  THEN '胖龙王'
        WHEN partner_id = 1701171101021  THEN '金佩奇'
        WHEN partner_id = 1701171101022  THEN '速用花'
        WHEN partner_id = 1701171101027  THEN '红佩奇'
        WHEN partner_id = 1701171101028  THEN '香蕉船'
        ELSE NULL
    END AS '合作方',

    CONCAT(LEFT(mobile, 3),'****',RIGHT(mobile, 4)),
    CONCAT(LEFT(id_num, 10), '****', RIGHT(id_num, 4)),
    zw_order,
    score,
    FROM_UNIXTIME(add_time)
FROM `fengkong3_log`
WHERE partner_id IN (1701171101015,1701171101019,1701171101020,1701171101021,1701171101022,1701171101027,1701171101028)
AND score > 0
AND add_time BETWEEN 1556640000 AND 1562169600





/************************************************************\
 **                                                        **
 **                        国内风控                         **
 **                                                        **
 **             按日期统计每天「通过率」【630 | 650】           **
 **                                                        **
\************************************************************/
SELECT
    SUBSTR(FROM_UNIXTIME(add_time),1,10) AS '日期',
    COUNT(IF( score >0, id, NULL)) AS '总量',
    COUNT(IF( score >550, id, NULL)) AS '550以上',
    CONCAT(TRUNCATE(COUNT(IF( score >550, id, NULL)) / COUNT(IF( score >0, id, NULL)),4) * 100, '%') AS '550以上比率',
    COUNT(IF( score >530, id, NULL)) AS '530以上',
    CONCAT(TRUNCATE(COUNT(IF( score >530, id, NULL)) / COUNT(IF( score >0, id, NULL)),4) * 100, '%') AS '530以上比率',
    COUNT(IF( score >520, id, NULL)) AS '520以上',
    CONCAT(TRUNCATE(COUNT(IF( score >520, id, NULL)) / COUNT(IF( score >0, id, NULL)),4) * 100, '%') AS '520以上比率',
    COUNT(IF( score >500, id, NULL)) AS '500以上',
    CONCAT(TRUNCATE(COUNT(IF( score >500, id, NULL)) / COUNT(IF( score >0, id, NULL)),4) * 100, '%') AS '500以上比率'
FROM `fengkong3_log`
WHERE add_time >1564588800
AND partner_id IN (1701171101014)
AND score >0
GROUP BY 日期
AND partner_id IN (1701171101014) //天象
AND partner_id IN (1701171101001,1701171101004,1701171101009,1701171101010) //信用牛牛
AND partner_id IN (1701171101015,1701171101019,1701171101020,1701171101021,1701171101022,1701171101027,1701171101028) //小镜子
AND partner_id IN (1701171101005,1701171101006,1701171101007,1701171101008,1701171101011,1701171101013,1701171101018) //pdl




/************************************************************\
 **                                                        **
 **                        国内风控                         **
 **                                                        **
 **                贷后数据根据手机号提取订单号                 **
 **                                                        **
\************************************************************/
SELECT mobile,zw_order
FROM `fengkong3_log`
AND score > 0
WHERE partner_id IN (1701171101001,1701171101004,1701171101009,1701171101010)
AND  add_time BETWEEN 1556640000 AND 1561910400
AND mobile IN ()





/************************************************************\
 **                                                        **
 **                      印尼系统                            **
 **                                                        **
 **         注册---个人信息---工作信息---活体认证---下单        **
 **                                                        **
\************************************************************/
SELECT
    a.phone,
    a.platform,
    a.channel,
    IF(b.ktp_number = 0, '没有填写', b.ktp_number)         AS '个人信息',
    IF(d.career = '', '没有填写', d.career)                AS '工作信息',
    IF(b.face_url = '','没有认证','认证')                 AS '活体认证',
    IF(c.order_number IS NULL, '没有下单', c.order_number) AS '是否下单',
    a.create_time
FROM `tb_user_access`        AS a
LEFT JOIN tb_user_basic_info AS b ON a.user_id = b.uid
LEFT JOIN tb_loan_order      AS c ON a.user_id = c.uid
LEFT JOIN tb_user_work_info  AS d ON a.user_id = d.uid
where a.create_time >= '2020-02-26 00:00:00'
AND a.create_time <= '2019-08-24 00:00:00'



SELECT
    a.phone,
    a.platform,
    IF(b.name = '', '没有填写', b.name)                    AS '个人信息',
    IF(b.address = '', '没有填写', b.address)              AS '基本信息',
    IF(e.name = '', '没有填写', e.name)                    AS '联系人',
    IF(d.career = '', '没有填写', d.career)                AS '工作信息',
    IF(b.face_url = '','没有认证', b.face_url)             AS '活体认证',
    IF(f.bank_number = 0,'没有填写', f.bank_number)       AS '银行卡',
    IF(c.order_number IS NULL, '没有下单', c.order_number) AS '是否下单',
    a.create_time
FROM `tb_user_access`        AS a
LEFT JOIN tb_user_basic_info AS b ON a.user_id = b.uid
LEFT JOIN tb_loan_order      AS c ON a.user_id = c.uid
LEFT JOIN tb_user_work_info  AS d ON a.user_id = d.uid
left join (
    select * from tb_user_relative_info where id in (select min(id) from tb_user_relative_info group by uid)
) as e on a.user_id = e.uid
left join (
    select * from tb_user_bank_info where id in (select min(id) from  tb_user_bank_info group by uid)
) as f on a.user_id = f.uid
where a.create_time >= '2019-08-23 00:00:00'
AND a.create_time <= '2019-08-24 00:00:00'


/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                                 白名单: 注册，下单，放款，应还                                                 **
 **                                                                                                                           **
 **                                              phone IN (1)   替换成真实手机号号集合                                            **
 **                                                                                                                           **
\*******************************************************************************************************************************/
SELECT
    a.phone    AS '注册手机号',
    a.platform AS '平台',
    a.channel  AS '渠道',
    IF(order_number IS NULL, '未下单', order_number) AS '是否下单',
    CASE
        WHEN order_status = 1   THEN '创建订单'
        WHEN order_status = 2   THEN '2分钟内取消'
        WHEN order_status = 3   THEN '待自动放款'
        WHEN order_status = 4   THEN ''
        WHEN order_status = 5   THEN '终审失败'
        WHEN order_status = 6   THEN '终审成功'
        WHEN order_status = 7   THEN '放款审核未通过'
        WHEN order_status = 8   THEN '放款中'
        WHEN order_status = 9   THEN ''
        WHEN order_status = 10  THEN ''
        WHEN order_status = 11  THEN '已逾期'
        WHEN order_status = 12  THEN '已还清'
        WHEN order_status = 13  THEN '部分还款'
        WHEN order_status = 14  THEN '待还款'
        WHEN order_status = 15  THEN '放款失败'
        WHEN order_status = 16  THEN '风控队列中'
        WHEN order_status = 17  THEN '风控成功，待信审'
        WHEN order_status = 18  THEN '风控失败'
        WHEN order_status = 19  THEN '信审成功'
        WHEN order_status = 20  THEN '信审失败'
        WHEN order_status = 21  THEN '电审成功'
        WHEN order_status = 22  THEN '电审失败'
        WHEN order_status = 23  THEN '用户确认失败'
        WHEN order_status = 24  THEN '终止放款'
        WHEN order_status = 25  THEN '当天到期'
        ELSE '无'
    END AS '订单状态',
    CASE
        WHEN user_type = 1   THEN '首贷'
        WHEN user_type = 2   THEN '续贷'
        ELSE '无'
    END AS '用户类型',
    IFNULL(create_time,'无')         AS '注册时间',
    IFNULL(order_time,'无')          AS '下单时间',
    IFNULL(payment_date,'无')        AS '放款时间',
    IFNULL(loan_deadline,'无')       AS '应还时间',
    IFNULL(loan_repayment_date,'无') AS '实还时间'
FROM tb_user_access AS a
LEFT JOIN tb_loan_order AS b ON a.user_id = b.uid
LEFT JOIN tb_order_product_info AS c ON b.id = c.order_id
WHERE phone IN (
    select distinct phone from tb_sms_post where type = 2 and status = 2 and create_time > '2019-09-01'
)
and c.user_type = 1


/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                                  信审，电审，终审  审核记录                                                   **
 **                                                                                                                           **
\*******************************************************************************************************************************/

SELECT
    a.phone    AS '注册手机号',
    a.platform AS '平台',
    a.channel  AS '渠道',
    IF(b.order_number IS NULL, '未下单', b.order_number) AS '订单号',
    CASE
        WHEN order_status = 1   THEN '创建订单'
        WHEN order_status = 2   THEN '2分钟内取消'
        WHEN order_status = 3   THEN '待自动放款'
        WHEN order_status = 4   THEN ''
        WHEN order_status = 5   THEN '终审失败'
        WHEN order_status = 6   THEN '终审成功'
        WHEN order_status = 7   THEN '放款审核未通过'
        WHEN order_status = 8   THEN '放款中'
        WHEN order_status = 9   THEN ''
        WHEN order_status = 10  THEN ''
        WHEN order_status = 11  THEN '已逾期'
        WHEN order_status = 12  THEN '已还清'
        WHEN order_status = 13  THEN '部分还款'
        WHEN order_status = 14  THEN '待还款'
        WHEN order_status = 15  THEN '放款失败'
        WHEN order_status = 16  THEN '风控队列中'
        WHEN order_status = 17  THEN '风控成功，待信审'
        WHEN order_status = 18  THEN '风控失败'
        WHEN order_status = 19  THEN '信审成功'
        WHEN order_status = 20  THEN '信审失败'
        WHEN order_status = 21  THEN '电审成功'
        WHEN order_status = 22  THEN '电审失败'
        WHEN order_status = 23  THEN '用户确认失败'
        WHEN order_status = 24  THEN '终止放款'
        WHEN order_status = 25  THEN '当天到期'
        ELSE '无'
    END AS '订单状态',

    CASE
        WHEN reason_code = 101   THEN 'OK'
        WHEN reason_code = 102   THEN 'ktp编辑'
        WHEN reason_code = 103   THEN '60分以下'
        WHEN reason_code = 104   THEN '上传他人信息'
        WHEN reason_code = 105   THEN '二次模糊'
        WHEN reason_code = 106   THEN 'NPWP编辑'
        WHEN reason_code = 107   THEN '职业'
        WHEN reason_code = 108   THEN '其他：用户说不用了、骗子'

        -- WHEN reason_code = 201   THEN 'OK'
        -- WHEN reason_code = 202   THEN '联系人核验用户信息不一致'
        -- WHEN reason_code = 203   THEN '联系人信息虚假'
        -- WHEN reason_code = 204   THEN '通讯录中无关键联系人（父母/爱人/兄弟姐妹）'
        -- WHEN reason_code = 205   THEN '合作骗我们/联系人'
        -- WHEN reason_code = 206   THEN '公司信息虚假（公司不存在）'
        -- WHEN reason_code = 207   THEN '公司不认识用户'
        -- WHEN reason_code = 208   THEN '用户作假自己接电话/公司 '
        -- WHEN reason_code = 209   THEN '用户作假自己接电话/联系人'
        -- WHEN reason_code = 210   THEN '用户一直无人接听'
        -- WHEN reason_code = 211   THEN '用户失联（停机/关机/空号）'
        -- WHEN reason_code = 212   THEN '无通讯录'
        -- WHEN reason_code = 213   THEN '通讯录核实虚假'
        -- WHEN reason_code = 214   THEN '职业不符合要求'
        -- WHEN reason_code = 215   THEN '用户不借款了'
        -- WHEN reason_code = 216   THEN '其他'

        WHEN reason_code = 201   THEN 'OK'
        WHEN reason_code = 202   THEN '联系人核验用户信息不一致'
        WHEN reason_code = 203   THEN '联系人信息虚假'
        WHEN reason_code = 204   THEN '通讯录中无关键联系人（父母/爱人/兄弟姐妹）'
        WHEN reason_code = 205   THEN '合作骗我们/联系人'
        WHEN reason_code = 206   THEN '公司信息虚假（公司不存在）'
        WHEN reason_code = 207   THEN '公司不认识用户'
        WHEN reason_code = 208   THEN '用户作假自己接电话/公司 '
        WHEN reason_code = 209   THEN '用户作假自己接电话/联系人'
        WHEN reason_code = 210   THEN '用户一直无人接听'
        WHEN reason_code = 211   THEN '用户失联（停机/关机/空号）'
        WHEN reason_code = 212   THEN '无通讯录'
        WHEN reason_code = 213   THEN '通讯录核实虚假'
        WHEN reason_code = 214   THEN '职业不符合要求'
        WHEN reason_code = 215   THEN '用户不借款了'
        WHEN reason_code = 216   THEN '其他'
        WHEN reason_code = 217   THEN '两日本人未接通'
        WHEN reason_code = 218   THEN '两日公司未接通'

        WHEN reason_code = 301   THEN 'OK'
        WHEN reason_code = 302   THEN '黑名单'
        WHEN reason_code = 303   THEN 'KTP认证失败'
        WHEN reason_code = 304   THEN '催收难度高'
        WHEN reason_code = 305   THEN '逾期短信'
        WHEN reason_code = 306   THEN '骗子'
        WHEN reason_code = 307   THEN '通讯录匹配失败'
        WHEN reason_code = 308   THEN '通讯录拨打失败'
        WHEN reason_code = 309   THEN '其他'

        ELSE '无'
    END AS '审核原因',

    SUBSTRING(
        REPLACE(work, '}' , ','),
        LOCATE('dian_reason1":"', REPLACE(work, '}' , ',') ) + CHAR_LENGTH('dian_reason1":"'),
        LOCATE('",' ,REPLACE(work, '}' , ','), LOCATE('dian_reason1":"', REPLACE(work, '}' , ',')) + CHAR_LENGTH('dian_reason1":"'))-(LOCATE('dian_reason1":"' ,REPLACE(work,'}' ,',')) + CHAR_LENGTH('dian_reason1":"'))
    ) AS 'dian_reason1',

    SUBSTRING(
        REPLACE(peason, '}' , ','),
        LOCATE('dian_reason2":"', REPLACE(peason, '}' , ',') ) + CHAR_LENGTH('dian_reason2":"'),
        LOCATE('",' ,REPLACE(peason, '}' , ','), LOCATE('dian_reason2":"', REPLACE(peason, '}' , ',')) + CHAR_LENGTH('dian_reason2":"'))-(LOCATE('dian_reason2":"' ,REPLACE(peason,'}' ,',')) + CHAR_LENGTH('dian_reason2":"'))
    ) AS 'dian_reason2'

FROM tb_user_access AS a
LEFT JOIN tb_loan_order             AS b ON a.user_id = b.uid

-- LEFT JOIN tb_loan_trial_info_record AS c ON b.id = c.order_id
-- WHERE b.id in(SELECT order_id FROM `tb_order_status` where status = 20
-- and substr(created_at,1,10) > '2019-07-01')

LEFT JOIN tb_loan_trial_phone_record AS c ON b.id = c.order_id
WHERE b.id in(SELECT order_id FROM `tb_order_status` where status = 22
and substr(created_at,1,10) >= '2019-10-21'
and substr(created_at,1,10) <= '2019-10-26')

-- LEFT JOIN tb_loan_trial_final AS c ON b.id = c.order_id
-- WHERE b.id in(SELECT order_id FROM `tb_order_status` where status = 5 and substr(created_at,1,10) > '2019-07-01')

order by b.id


/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                               DompetPinjaman  系统通讯录外呼                                                **
 **                                                                                                                           **
\*******************************************************************************************************************************/

select
    order_number,
    CASE
        WHEN order_status = 1   THEN '创建订单'
        WHEN order_status = 2   THEN '2分钟内取消'
        WHEN order_status = 3   THEN '待自动放款'
        WHEN order_status = 4   THEN ''
        WHEN order_status = 5   THEN '终审失败'
        WHEN order_status = 6   THEN '终审成功'
        WHEN order_status = 7   THEN '放款审核未通过'
        WHEN order_status = 8   THEN '放款中'
        WHEN order_status = 9   THEN ''
        WHEN order_status = 10  THEN ''
        WHEN order_status = 11  THEN '已逾期'
        WHEN order_status = 12  THEN '已还清'
        WHEN order_status = 13  THEN '部分还款'
        WHEN order_status = 14  THEN '待还款'
        WHEN order_status = 15  THEN '放款失败'
        WHEN order_status = 16  THEN '风控队列中'
        WHEN order_status = 17  THEN '风控成功，待信审'
        WHEN order_status = 18  THEN '风控失败'
        WHEN order_status = 19  THEN '信审成功'
        WHEN order_status = 20  THEN '信审失败'
        WHEN order_status = 21  THEN '电审成功'
        WHEN order_status = 22  THEN '电审失败'
        WHEN order_status = 23  THEN '用户确认失败'
        WHEN order_status = 24  THEN '终止放款'
        WHEN order_status = 25  THEN '当天到期'
        ELSE '无'
    END AS '订单状态',
    CASE
        WHEN phone_status = 0   THEN '未接通'
        WHEN phone_status = 1   THEN '接通'
        ELSE '无'
    END AS '外呼',
    order_time,
    payment_date,
    loan_repayment_date

from tb_loan_order
left join tb_order_product_info on tb_loan_order.id = tb_order_product_info.order_id
left join tb_loan_trial_phone_contact on tb_loan_order.id = tb_loan_trial_phone_contact.order_id

where order_time > '2019-12-01 00:00:00'
and user_type = 1
and platform = 'DompetPinjaman'


/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                                      DompetSaku  分数                                                      **
 **                                                                                                                           **
\*******************************************************************************************************************************/

SELECT
    a.user_id  AS '用户ID',
    a.phone    AS '注册手机号',
    a.platform AS '平台',
    a.channel  AS '渠道',
    IF(b.order_number IS NULL, '未下单', b.order_number) AS '是否下单',
    IF(d.extend_score IS NULL, '无', d.extend_score) AS '分数',
    IF(d.v_type IS NULL, '无', d.v_type) AS '版本',
    CASE
        WHEN b.order_status = 1   THEN '创建订单'
        WHEN b.order_status = 2   THEN '2分钟内取消'
        WHEN b.order_status = 3   THEN '待自动放款'
        WHEN b.order_status = 4   THEN ''
        WHEN b.order_status = 5   THEN '终审失败'
        WHEN b.order_status = 6   THEN '终审成功'
        WHEN b.order_status = 7   THEN '放款审核未通过'
        WHEN b.order_status = 8   THEN '放款中'
        WHEN b.order_status = 9   THEN ''
        WHEN b.order_status = 10  THEN ''
        WHEN b.order_status = 11  THEN '已逾期'
        WHEN b.order_status = 12  THEN '已还清'
        WHEN b.order_status = 13  THEN '部分还款'
        WHEN b.order_status = 14  THEN '待还款'
        WHEN b.order_status = 15  THEN '放款失败'
        WHEN b.order_status = 16  THEN '风控队列中'
        WHEN b.order_status = 17  THEN '风控成功，待信审'
        WHEN b.order_status = 18  THEN '风控失败'
        WHEN b.order_status = 19  THEN '信审成功'
        WHEN b.order_status = 20  THEN '信审失败'
        WHEN b.order_status = 21  THEN '电审成功'
        WHEN b.order_status = 22  THEN '电审失败'
        WHEN b.order_status = 23  THEN '用户确认失败'
        WHEN b.order_status = 24  THEN '终止放款'
        WHEN b.order_status = 25  THEN '当天到期'
        ELSE '无'
    END AS '订单状态',
    CASE
        WHEN user_type = 1   THEN '首贷'
        WHEN user_type = 2   THEN '续贷'
        ELSE '无'
    END AS '用户类型',
    IFNULL(a.create_time,'无')         AS '注册时间',
    IFNULL(b.order_time,'无')          AS '下单时间',
    IFNULL(b.payment_date,'无')        AS '放款时间',
    IFNULL(b.loan_deadline,'无')       AS '应还时间',
    IFNULL(b.loan_repayment_date,'无') AS '实还时间'
FROM tb_user_access AS a
LEFT JOIN tb_loan_order AS b ON a.user_id = b.uid
LEFT JOIN tb_order_product_info AS c ON b.id = c.order_id
LEFT JOIN tb_fengkong_log AS d ON b.id = d.order_id
WHERE a.create_time >= '2020-02-26 00:00:00'
WHERE a.platform = 'UangTas' and a.channel = 'uangcash'
and b.platform in ('DompetSaku', 'DompetPinjaman')


/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                                  DompetPinjaman  整月数据                                                   **
 **                                                                                                                           **
\*******************************************************************************************************************************/
SELECT phone,a.create_time,order_number,b.order_time
FROM `tb_user_access` as a
left join tb_loan_order as b on a.user_id = b.uid
WHERE a.platform = 'DompetPinjaman' and a.create_time >='2020-02-01 00:00:00'




/*******************************************************************************************************************************\
 **                                                                                                                           **
 **                                                        印尼系统                                                            **
 **                                                                                                                           **
 **                                                  DOKU 还款银行和方式                                                       **
 **                                                                                                                           **
\*******************************************************************************************************************************/
SELECT order_number,order_status,bank_name,bank_id,bank_number,create_time FROM `tb_user_bank_info` as a
left join tb_loan_order as b on a.oid = b.id
where a.id in (
    select max(id) as bank_id from tb_user_bank_info
    where oid in (
        select order_id from (SELECT order_id FROM `tb_loan_repayment_flow`  order by id desc limit 30) as tt
    )
    group by oid
)







#最近7天续贷应还，每个用户的贷款次数，包括是否还款
SELECT *,
(
    SELECT count(*) AS cc
    FROM tb_loan_order AS t1
    INNER JOIN tb_order_product_info AS t2 ON t1.id = t2.order_id
    WHERE t1.uid = tt.用户ID
    AND t2.user_type = 2
    AND t1.order_time <= tt.下单时间
) AS '续贷次数'
 FROM
(
    SELECT
    c.phone               AS '手机号',
    c.user_id             AS '用户ID',
    c.platform            AS '平台',
    c.channel             AS '渠道',
    a.order_number        AS '订单号',
    a.order_time          AS '下单时间',
    a.payment_date        AS '放款时间',
    a.loan_deadline       AS '应还时间',
    a.loan_repayment_date AS '实还时间',
    CASE
        WHEN a.order_status = 1   THEN '创建订单'
        WHEN a.order_status = 2   THEN '2分钟内取消'
        WHEN a.order_status = 3   THEN '待自动放款'
        WHEN a.order_status = 4   THEN ''
        WHEN a.order_status = 5   THEN '终审失败'
        WHEN a.order_status = 6   THEN '终审成功'
        WHEN a.order_status = 7   THEN '放款审核未通过'
        WHEN a.order_status = 8   THEN '放款中'
        WHEN a.order_status = 9   THEN ''
        WHEN a.order_status = 10  THEN ''
        WHEN a.order_status = 11  THEN '已逾期'
        WHEN a.order_status = 12  THEN '已还清'
        WHEN a.order_status = 13  THEN '部分还款'
        WHEN a.order_status = 14  THEN '待还款'
        WHEN a.order_status = 15  THEN '放款失败'
        WHEN a.order_status = 16  THEN '风控队列中'
        WHEN a.order_status = 17  THEN '风控成功，待信审'
        WHEN a.order_status = 18  THEN '风控失败'
        WHEN a.order_status = 19  THEN '信审成功'
        WHEN a.order_status = 20  THEN '信审失败'
        WHEN a.order_status = 21  THEN '电审成功'
        WHEN a.order_status = 22  THEN '电审失败'
        WHEN a.order_status = 23  THEN '用户确认失败'
        WHEN a.order_status = 24  THEN '终止放款'
        WHEN a.order_status = 25  THEN '当天到期'
        ELSE '无'
    END AS '订单状态',
    CASE
        WHEN user_type = 1   THEN '首贷'
        WHEN user_type = 2   THEN '续贷'
        ELSE '无'
    END AS '用户类型'
    FROM tb_loan_order AS a
    LEFT JOIN tb_order_product_info AS b ON a.id = b.order_id
    LEFT JOIN tb_user_access AS c ON a.uid = c.user_id
    WHERE b.user_type = 2
    AND a.loan_deadline >= '2020-01-22 00:00:00'
    AND a.order_status IN (11,12,13,14,25)
    ORDER BY a.loan_deadline, a.order_time
) AS tt


# 从数据库统计下KSP后台3月26日之后，每个催收人员的催回金额

SELECT user.admin_username, sum(c.repayment_amount)
FROM tb_loan_order as a
left join tb_loan_order_track as b on a.id = b.order_id
left join tb_loan_repayment_flow as c on a.id = c.order_id
left join tb_admin_user as user on b.admin_id = user.id
left join tb_admin_role_info as role on user.id = role.aid
where user.is_del = 0
and user.admin_status = 0
and user.group_id = 1
and role.status = 0
and role.rid = 4
and is_remind = 0
and repayment_status = 1
and c.created_at >= '2020-03-26'
group by b.admin_id


select * from
(
    SELECT
    GROUP_CONCAT( user_id ) ,
    COUNT( id ) AS cc,
    COUNT( distinct user_id ) AS cc2,
    COUNT( distinct trade_type ) AS cc3,
    SUBSTR( FROM_UNIXTIME( `build_time` ) , 1, 16 ) ,
    `goods_id` ,
    `trade_deposit`

    FROM `user_order`
    WHERE SUBSTR( FROM_UNIXTIME( `build_time` ) , 1, 10 ) = '2020-04-08'
    GROUP BY `goods_id` , `trade_deposit` , SUBSTR( FROM_UNIXTIME( `build_time` ) , 1, 16 )

) as tt where tt.cc > 1  and cc3 = 2
ORDER BY tt.cc DESC



# 销量前十的产品排序
select t.cat_id,t.goods_num, top
from
(
    select t1.*, (
          select count(*) + 1 from order_goods t2
          where t2.cat_id = t1.cat_id and t2.goods_num > t1.goods_num
    ) top
    from order_goods t1
) t
where top <=10 order by t.cat_id, top;

SELECT *
FROM `user_saving_order`
WHERE SUBSTR( FROM_UNIXTIME( `add_time` ) , 1, 10 ) = '2020-05-11'
AND user_id IN (
    SELECT id
    FROM user
    WHERE SUBSTR( FROM_UNIXTIME( `reg_time` ) , 1, 10 ) = '2020-05-11'
)


#  后台加个“充值转化的模块”展示上面的数据
select
SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) as '日期',
COUNT(*) AS '总充值发起',
COUNT(CASE WHEN (uso.status = 1) THEN 1 ELSE NUll END) AS '总完成充值',
COUNT(CASE WHEN (uso.status = 1) THEN 1 ELSE NUll END) / COUNT(*) as '总充值率',
COUNT(CASE WHEN (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) != SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10)) THEN 1 ELSE NUll END) AS '老用户发起',
COUNT(CASE WHEN ((uso.status = 1) and (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) != SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10))) THEN 1 ELSE NUll END) AS '老用户完成',
COUNT(CASE WHEN ((uso.status = 1) and (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) != SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10))) THEN 1 ELSE NUll END) /
COUNT(CASE WHEN (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) != SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10)) THEN 1 ELSE NUll END) as '老用户充值率',
COUNT(CASE WHEN (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) = SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10)) THEN 1 ELSE NUll END) AS '新用户发起',
COUNT(CASE WHEN ((uso.status = 1) and (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) = SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10))) THEN 1 ELSE NUll END) AS '新用户完成',
COUNT(CASE WHEN ((uso.status = 1) and (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) = SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10))) THEN 1 ELSE NUll END) /
COUNT(CASE WHEN (SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) = SUBSTR(FROM_UNIXTIME(u.reg_time), 1, 10)) THEN 1 ELSE NUll END) as '新用户充值率'
from user_saving_order as uso
left join user as u on  uso.user_id = u.id
where SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) between '2020-05-01' and '2020-05-15'
GROUP BY SUBSTR(FROM_UNIXTIME(uso.add_time), 1, 10) DESC



#========

select
d.Name as Department,
e.Name as Employee,
e.Salary as Salary
from Employee as e left join Department as d
on e.DepartmentId = d.Id
where e.Id in
(
    select e1.Id
    from Employee as e1 left join Employee as e2
    on e1.DepartmentId = e2.DepartmentId and e1.Salary < e2.Salary
    group by e1.Id
    having count(distinct e2.Salary) <= 2
)
and e.DepartmentId in (select Id from Department)
order by d.Id asc,e.Salary desc






