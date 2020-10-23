-- 用户数
SELECT distinct uso.user_id,
        (
        	CASE 
					WHEN count(uso.saving_num)>=1200 AND (uo.build_price)>8000  THEN 120
					WHEN count(uso.saving_num)>=500 AND count(uso.saving_num)<3000 AND (uo.build_price)>3000  THEN 45
       		WHEN count(uso.saving_num)>=200 AND count(uso.saving_num)<500 AND (uo.build_price)>1200  THEN 15
          END
        )  integral
FROM `user_saving_order` AS uso
LEFT JOIN `user_order` AS uo ON uo.user_id=uso.user_id
 WHERE uo.liqui_time>=1572192000 AND  uo.liqui_time<1572710400
 GROUP BY uso.user_id HAVING `integral`IS NOT NULL;