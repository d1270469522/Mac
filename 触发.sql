


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
    IF(b.ktp_number = 0, '没有填写', b.ktp_number)         AS '个人信息',
    IF(d.career = '', '没有填写', d.career)                AS '工作信息',
    IF(b.face_url = '','没有认证','认证')                 AS '活体认证',
    IF(c.order_number IS NULL, '没有下单', c.order_number) AS '是否下单',
    a.create_time
FROM `tb_user_access`        AS a
LEFT JOIN tb_user_basic_info AS b ON a.user_id = b.uid
LEFT JOIN tb_loan_order      AS c ON a.user_id = c.uid
LEFT JOIN tb_user_work_info  AS d ON a.user_id = d.uid
where a.create_time >= '2019-08-23 00:00:00'
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
    END AS '订单状态',
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

where order_time > '2019-11-25 00:00:00'
and user_type = 1
and platform = 'DompetPinjaman'










