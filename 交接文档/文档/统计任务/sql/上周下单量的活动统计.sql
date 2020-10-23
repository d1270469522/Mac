SELECT u.mobile AS 手机号,SUM(uo.`trade_deposit`) AS 金额,uo.`user_id`
FROM `user_order`AS uo
LEFT JOIN `user` AS u ON u.`id` = uo.`user_id`
WHERE uo.`build_time`>=1575820800 AND uo.`build_time`<1576425600 AND uo.`use_ticket` =0
GROUP BY uo.`user_id`HAVING 金额>=7800 LIMIT 1000