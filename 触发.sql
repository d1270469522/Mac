


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






/*************************************************************************************************************************\
 **                                                                                                                     **
 **                                              印尼系统                                                                **
 **                                                                                                                     **
 **                                 新风控 : 注册，下单，放款，应还                                                         **
 **                                                                                                                     **
 **                                 c.flag = 2                    标识：新风控                                            **
 **                                 d.user_type = 1               用户类型：首贷用户                                       **
 **                                 b.order_time > '2019-06-27'   由于刷过数据，2019-06-27之前也会查询出来，所以排除掉         **
 **                                 e.id IN (**)                  新风控的记录有多条，最后一条获取分数的记录                   **
 **                                                                                                                     **
 **                                                                                                                     **
\*************************************************************************************************************************/
SELECT
    a.phone    AS '注册手机号',
    a.platform AS '平台',
    a.channel  AS '渠道',
    IF(b.order_number IS NULL, '未下单', b.order_number) AS '是否下单',
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
    e.score,
    IFNULL(a.create_time,'无')         AS '注册时间',
    IFNULL(b.order_time,'无')          AS '下单时间',
    IFNULL(b.payment_date,'无')        AS '放款时间',
    IFNULL(b.loan_deadline,'无')       AS '应还时间',
    IFNULL(b.loan_repayment_date,'无') AS '实还时间'
FROM tb_user_access AS a
LEFT JOIN tb_loan_order            AS b ON a.user_id = b.uid
LEFT JOIN tb_order_user_basic_info AS c ON b.basic_id = c.id
LEFT JOIN tb_order_product_info    AS d ON b.id = d.order_id
LEFT JOIN tb_yinni_risk_log        AS e ON b.id = e.order_id
WHERE c.flag = 2
AND d.user_type = 1
AND b.order_time > '2019-08-22'
AND e.id IN (SELECT MAX(id) FROM tb_yinni_risk_log WHERE type IN ('getNewUserRisk', 'getUserRisk') GROUP BY order_id)





/*************************************************************************************************************************\
 **                                                                                                                     **
 **                                              印尼系统                                                                **
 **                                                                                                                     **
 **                               还款情况 : 按每天小时统计：提前还款 / 当天还款 / 逾期未还                                     **
 **                                                                                                                     **
\*************************************************************************************************************************/
SELECT
    COUNT(IFNULL( loan_repayment_date, NULL)) AS num,
    COUNT(IF( loan_repayment_date < loan_deadline, 1, NULL)) AS num1,
    COUNT(IF( SUBSTR(loan_repayment_date,1,10) = SUBSTR(loan_deadline,1,10), 1, NULL)) AS num2,
    COUNT(IF( loan_repayment_date > loan_overdue_date, 1, NULL)) AS num3,
    DATE_FORMAT( loan_repayment_date , '%Y-%m-%d %H:00' ) AS hours
FROM `tb_loan_order`
WHERE loan_repayment_date >= "2019-07-14"
AND loan_repayment_date < "2019-07-15"
GROUP BY `hours`




/**
 *
 */
/****************************************************************************************************************************\
 **                                                                                                                        **
 **                                   信审、电审、终审  都是有哪些管理员                                                        **
 **                                                                                                                        **
\****************************************************************************************************************************/
select
    `tb_admin_role_info`.`rid`,
    `tb_admin_role_info`.`aid`,
    `admin_username`
from `tb_admin_role_info`
inner join `tb_admin_role` on `tb_admin_role_info`.`rid` = `tb_admin_role`.`rid`
inner join `tb_admin_user` on `tb_admin_role_info`.`aid` = `tb_admin_user`.`id`
where `tb_admin_role`.`delete` = 0
and `tb_admin_role_info`.`status` = 0
and `tb_admin_user`.`admin_status` = 0
and `tb_admin_user`.`is_del` = 0
and `tb_admin_role_info`.`rid` in (2,3,10)
and `admin_username` not in ('测试')



/**
 * 信审，某个管理员还没有审核的单子
 */
select count(*) as aggregate
from `tb_loan_order`
left join `tb_loan_trial_info` on `tb_loan_trial_info`.`order_id` = `tb_loan_order`.`id`
where `admin_id` = 21 and `order_status` = 17

/**
 * 电审，某个管理员还没有审核的单子
 */
select count(*) as aggregate
from `tb_loan_order`
left join `tb_loan_trial_phone` on `tb_loan_trial_phone`.`order_id` = `tb_loan_order`.`id`
left join `tb_loan_trial_phone_record` on `tb_loan_trial_phone_record`.`order_id` = `tb_loan_order`.`id`
where `tb_loan_trial_phone`.`admin_id` = 21
and (
        (`tb_loan_trial_phone_record`.`work_phone_status` not in (2) and `tb_loan_trial_phone_record`.`phone_status` not in (2))
        or
        (`tb_loan_trial_phone_record`.`phone_status` is null)
        or
        (`tb_loan_trial_phone_record`.`work_phone_status` is null)
    )
and `order_status` = 19

/**
 * 终审，某个管理员还没有审核的单子
 */
select count(*) as aggregate
from `tb_loan_order`
left join `tb_loan_trial_final` on `tb_loan_trial_final`.`order_id` = `tb_loan_order`.`id`
where `admin_id` = 1 and `order_status` = 21





/********************************************************************************************************************\
 **                                                                                                                **
 **                                           18日放款数据 --- 首贷用户                                               **
 **                                                                                                                **
\********************************************************************************************************************/
SELECT
    a.phone    AS '注册手机号',
    a.platform AS '平台',
    a.channel  AS '渠道',
    IF(b.order_number IS NULL, '未下单', b.order_number) AS '是否下单',
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
    e.score,
    IFNULL(a.create_time,'无')         AS '注册时间',
    IFNULL(b.order_time,'无')          AS '下单时间',
    IFNULL(b.payment_date,'无')        AS '放款时间',
    IFNULL(b.loan_deadline,'无')       AS '应还时间',
    IFNULL(b.loan_repayment_date,'无') AS '实还时间'

FROM tb_user_access AS a
LEFT JOIN tb_loan_order            AS b ON a.user_id = b.uid
LEFT JOIN tb_order_user_basic_info AS c ON b.basic_id = c.id
LEFT JOIN tb_order_product_info    AS d ON b.id = d.order_id
LEFT JOIN
    (select * from tb_yinni_risk_log
        where id IN (
            SELECT MAX(id) FROM tb_yinni_risk_log WHERE type IN ('getNewUserRisk', 'getUserRisk') GROUP BY order_id
        )
    ) AS e ON b.id = e.order_id

WHERE d.user_type = 1
AND substr(b.payment_date,1,10) >= '2019-07-01'
and ( (substr(b.order_time,1,10) > '2019-06-27' and e.score is null) or substr(b.order_time,1,10) <= '2019-06-27' )
and ( substr(b.order_time,1,10) > '2019-06-27' and e.score is not null )






/**
 * [admin_id 给21派单了，但是没有在order_status中添加记录]
 * @type {[type]}
 */
SELECT order_id FROM `tb_loan_trial_phone` where order_id not in (
    SELECT order_id FROM `tb_order_status` WHERE status in (21,22) and admin_id = 21)
and admin_id = 21



select
    COALESCE(b.platform, '总共') AS '平台',
    CASE
        WHEN c.user_type = 1   THEN '新用户'
        WHEN c.user_type = 2   THEN '老用户'
        ELSE '合计'
    END AS '新老用户',
    count(a.id) as '放款单量',
    count(if(a.order_status = 14, 1, null)) as '放款未到期',
    count(if(a.order_status = 25, 1, null)) as '当天到期',
    count(if(a.order_status = 12, 1, null)) as '已还清',
    count(if(a.order_status = 13, 1, null)) as '部分还款',
    count(if(a.order_status = 11, 1, null)) as '逾期未还',
    count(if( (a.order_status in (11,13) or (a.order_status = 12 and loan_repayment_date > loan_overdue_date)), 1, null)) as '触发'
from tb_loan_order as a
left join tb_user_access as b on a.uid = b.user_id
left join tb_order_product_info as c on a.id = c.order_id
left join tb_order_user_basic_info as d on a.basic_id = d.id
where a.order_status in (11,12,13,14,25)
group by b.platform desc , c.user_type desc
WITH ROLLUP



select
    SUBSTR( a.loan_deadline,  1, 10) AS '平台',
    count(a.id) as '放款单量',
    count(if(a.order_status = 14, 1, null)) as '放款未到期',
    count(if(a.order_status = 14, 1, null)) / count(a.id) as '未到期%',
    count(if(a.order_status = 25, 1, null)) as '当天到期',
    count(if(a.order_status = 25, 1, null)) / count(a.id) as '当天到期%',
    count(if(a.order_status = 12, 1, null)) as '已还清',
    count(if(a.order_status = 12, 1, null)) / count(a.id) as '已还清%',
    count(if(a.order_status = 13, 1, null)) as '部分还款',
    count(if(a.order_status = 13, 1, null)) / count(a.id) as '部分还款%',
    count(if(a.order_status = 11, 1, null)) as '逾期未还',
    count(if(a.order_status = 11, 1, null)) / count(a.id) as '逾期未还%',
    count(if( (a.order_status in (11,13) or (a.order_status = 12 and loan_repayment_date > loan_overdue_date)), 1, null)) as '触发',
    count(if( (a.order_status in (11,13) or (a.order_status = 12 and loan_repayment_date > loan_overdue_date)), 1, null)) / count(a.id) as '触发比例'
from tb_loan_order as a
left join tb_user_access as b on a.uid = b.user_id
left join tb_order_product_info as c on a.id = c.order_id
left join tb_order_user_basic_info as d on a.basic_id = d.id
where a.order_status in (11,12,13,14,25)
group by SUBSTR( a.loan_deadline,  1, 10)
order by 触发比例 desc

select

from (
    SELECT ktp_number,name FROM `tb_user_basic_info` where ktp_number not in (0, '') and ktp_number = '1017171606800001' group by ktp_number
) as tt



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


select
    tmp2.贷款次数,
    tmp2.贷款次数 - 1 as '续贷次数',
    count(tmp2.uid) as '用户个数',
    count(CASE WHEN (tmp2.每个用户还款中单量 > 0) THEN 1 ELSE NUll END) as '还款中个数',
    count(CASE WHEN (tmp2.每个用户逾期中单量 > 0) THEN 1 ELSE NUll END) as '逾期中个数'
from
    (
        SELECT
            tmp.uid,
            count(*) as '贷款次数',
            COUNT(CASE WHEN (tmp.order_status = 14) THEN 1 ELSE NUll END) AS '每个用户还款中单量',
            COUNT(CASE WHEN (tmp.order_status in (11,13)) THEN 1 ELSE NUll END) AS '每个用户逾期中单量'
        from
            (select id,uid,order_status,loan_overdue_date,loan_repayment_date from tb_loan_order where uid in
                (select uid from tb_loan_order where id in
                    (
                        select order_id from tb_order_product_info where user_type = 2
                    ) and id in
                    (
                        select id from tb_loan_order where order_status in (11,13,14)
                    )
                )
            )
            as tmp
        group by tmp.uid
    ) as tmp2
group by tmp2.贷款次数


select
    tmp2.贷款次数,
    tmp2.贷款次数 - 1 as '续贷次数',
    count(tmp2.uid) as '用户个数',
    count(CASE WHEN (tmp2.每个用户正常还款单量 > 0) THEN 1 ELSE NUll END) as '正常还款个数',
    count(CASE WHEN (tmp2.每个用户逾期还款单量 > 0) THEN 1 ELSE NUll END) as '逾期还款个数'
from
    (
        SELECT
            tmp.uid,
            count(*) as '贷款次数',
            COUNT(CASE WHEN (tmp.order_status = 12 and tmp.loan_repayment_date < tmp.loan_overdue_date) THEN 1 ELSE NUll END) AS '每个用户正常还款单量',
            COUNT(CASE WHEN (tmp.order_status = 12 and tmp.loan_repayment_date >= tmp.loan_overdue_date) THEN 1 ELSE NUll END) AS '每个用户逾期还款单量'
        from
            (select id,uid,order_status,loan_overdue_date,loan_repayment_date from tb_loan_order where uid in
                (select uid from tb_loan_order where id in
                    (
                        select order_id from tb_order_product_info where user_type = 2
                    ) and id in
                    (
                        select id from tb_loan_order where order_status in (11,13,14)
                    )
                )
            )
            as tmp
        group by tmp.uid
    ) as tmp2
group by tmp2.贷款次数


INSERT INTO `tb_blacklist` (`admin_id`, `name`, `ktp`, `phone`, `device`, `result`, `type`, `status`, `create_time`, `update_time`) VALUES
(1, 'Mitia Wardhani', '3275024810920012', '87884965824', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Wahyudi', '3174031703900006', '85691286183', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Rangga Randana', '3173063005930005', '87784447071', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Samuel Giamto', '3171041804950002', '81213520711', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Devita Dian Angraini', '3275026206920012', '81299600955', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Amos Parlin Rio S', '3175090309889008', '82238991716', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Fajar kristianto', '3276033012910006', '81280130963', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Rizki Suprianto', '3172050405890004', '85891452214', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Gianda Verma', '1304131304910001', '82126407459', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Euis Handayani', '3171047007920001', '87888995223', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Dodi Raendra', '3171061701920001', '85763696369', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Jesika Simbolon', '1210016705950003', '82160267991', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Marthin Luther Saragi', '3175071010950001', '82112596140', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Ady Murpy', '3174010301950004', '85921624375', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Panji Putra Perdana', '3175013009960004', '89512082806', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Muhammad Dava', '3175021606931001', '87860845154', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Kusminarti', '3201066212910003', '82213657773', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Fury Widya Ningrum', '3172026309970002', '85219000114', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Arby Wicaksono', '3275010812950026', '81398888727', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Hadi Yunanto', '3171031206980003', '83893123297', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Miftahul Jannah', '3175034610930004', '81382665823', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Rudi Rachmadi', '3275013003940008', '895334258401', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Vidya Paramitha', '3275035002870014', '81212899322', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Yogo Abiyadi', '3275021407910017', '81281773264', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Pahira Hardiyani Novianty', '3172036811010003', '85779054042', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49'),
(1, 'Alfi Kori', '3172021002880006', '89678560323', '', '2', 6, 1, '2019-10-23 04:50:48', '2019-10-23 04:53:49');


select
    COALESCE(tmp2.贷款次数) as '贷款次数',
    COALESCE(tmp2.贷款次数) - 1  as '续贷次数',
    tmp2.每个用户正常还款单量 as '正常还款单量',
    count(tmp2.uid) as '用户个数'
from
    (
        SELECT
            tmp.uid,
            count(*) as '贷款次数',
            COUNT(CASE WHEN (tmp.order_status = 12 and tmp.loan_repayment_date < tmp.loan_overdue_date) THEN 1 ELSE NUll END) AS '每个用户正常还款单量'
        from
            (select id,uid,order_status,loan_overdue_date,loan_repayment_date from tb_loan_order where uid in
                (select uid from tb_loan_order where id in
                    (
                        select order_id from tb_order_product_info where user_type = 2
                    ) and id in
                    (
                        select id from tb_loan_order where order_status in (11,13,14)
                    )
                )
            )
            as tmp
        group by tmp.uid
    ) as tmp2
group by tmp2.贷款次数 asc , tmp2.每个用户正常还款单量 asc
WITH ROLLUP

#逾期用户，手机型号统计
select mobileType, count(*) as cc from
    (
        select * from
        (
            select t.phone, t.mobileType from
            (
                SELECT phone,mobileType FROM `tb_user_access_log` order by id desc
            ) as t group by t.phone
        ) as t2
        where t2.phone in
        (
            SELECT phone FROM `tb_user_access` where user_id in
            (
                SELECT uid FROM `tb_loan_order` where order_status = 12 and loan_repayment_date < loan_overdue_date
            )
        )
    ) as t3
group by mobileType order by cc desc


select
    tb_loan_order.order_number,
    tb_loan_order.order_time,
    tb_loan_order.platform,
    tb_user_access.channel
from tb_loan_order
left join tb_user_access on tb_loan_order.uid = tb_user_access.user_id

where channel = 'cashcash'
and order_time between '2019-11-01 00:00:00' and '2019-12-01 00:00:00'
order by tb_loan_order.order_time


SELECT
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
    order_time,
    IF(b.user_type = 1, '首贷', '续贷') AS '用户类型'
FROM `tb_loan_order` as a
left join tb_order_product_info as b on a.id = b.order_id
where platform = 'DompetPinjaman' and substr(order_time,1,10) >= '2019-11-11' and substr(order_time,1,10) <= '2019-11-13'


update tb_sms_post set is_audit = 0
where phone (
    select phone from tb_collect_record where id in (
        select max(id) from tb_collect_record where phone in (
            SELECT phone FROM `tb_sms_post`  where platform = 'UangTas' and is_audit = 1
        )
        group by phone
    ) and status = 2
)
and is_audit = 1
and platform = 'UangTas'


select
    order_number,
    order_time,
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
    payment_date,
    loan_repayment_date

from tb_loan_order
left join tb_order_product_info on tb_loan_order.id = tb_order_product_info.order_id
left join tb_loan_trial_phone_contact on tb_loan_order.id = tb_loan_trial_phone_contact.order_id

where order_time > '2019-11-01 00:00:00'
and user_type = 1
and platform = 'DompetPinjaman'










