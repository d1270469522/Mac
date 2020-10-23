SELECT  `user_id`,FROM_UNIXTIME(`liqui_time`,'%Y-%m-%d %T') AS liqui_time  FROM `user_order` WHERE `liqui_time`>=1572796800  AND `liqui_time`<1573401600
GROUP BY `user_id`
ORDER BY `liqui_time` 