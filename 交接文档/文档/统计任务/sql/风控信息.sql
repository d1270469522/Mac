SELECT * 
FROM  `user_order` 
WHERE  `user_id` =64130 AND `liqui_time`>=1572796800 AND `liqui_time`<1572883200


SELECT  u.mobile,u.nickname,uo.`user_id`,g.pro_name,g.amount,uo.`goods_id`, sum(uo.trade_deposit) as trade
FROM  `user_order`  AS uo
LEFT JOIN `user` AS u ON u.id = uo.`user_id`
LEFT JOIN `goods` AS g ON g.`goods_id` = uo.`goods_id`
WHERE  uo.`user_id` =64130 AND uo.`liqui_time`>=1572796800 AND uo.`liqui_time`<1572883200
---------------------------------------------------------------------------------------------------

SELECT * 
FROM  `user_order` 
WHERE  `user_id` =64234 AND `liqui_time`>=1572796800 AND `liqui_time`<1572883200


SELECT  u.mobile,u.nickname,uo.`user_id`,g.pro_name,g.amount,uo.`goods_id`, sum(uo.trade_deposit) as trade
FROM  `user_order`  AS uo
LEFT JOIN `user` AS u ON u.id = uo.`user_id`
LEFT JOIN `goods` AS g ON g.`goods_id` = uo.`goods_id`
WHERE  uo.`user_id` =64234 AND uo.`liqui_time`>=1572796800 AND uo.`liqui_time`<1572883200