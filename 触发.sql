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
        if(h.order_status IN (12), 1, NULL) AS '实还笔数',
        if(h.order_status IN (12), (h.repayment_amount - h.overdue_amount), 0) AS '实还本息',
        if(h.order_status IN (12), h.overdue_amount, 0) AS '实还逾期费',
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
WITH ROLLDOWN


SELECT
    CASE
        WHEN partner_id = 1701171101015  THEN '小镜子'
        WHEN partner_id = 1701171101019  THEN '随身宝'
        WHEN partner_id = 1701171101020  THEN '胖龙王'
        WHEN partner_id = 1701171101021  THEN '金佩奇'
        WHEN partner_id = 1701171101022  THEN '速用花'
        ELSE NULL
    END AS '合作方',

    CONCAT(LEFT(mobile, 3),'****',RIGHT(mobile, 4)),
    CONCAT(LEFT(id_num, 10), '****', RIGHT(id_num, 4)),
    zw_order,
    scores,
    from_unixtime(add_time)
FROM `fengkong3_log`
WHERE partner_id IN (1701171101015,1701171101019,1701171101020,1701171101021,1701171101022)
AND scores > 0 AND add_time BETWEEN 1556640000 AND 1562169600







