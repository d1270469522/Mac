 -- 订单数
SELECT  user_id,
	CASE 
	`goods_id` WHEN 544946952 THEN '大规格'
  		   WHEN 544991584 THEN '大规格'
                   WHEN 544261936 THEN '大规格'
                   WHEN 686027832 THEN '大规格'
                   WHEN 511236720 THEN '大规格'
                   WHEN 545002240 THEN '大规格'
                   WHEN 644647808 THEN '大规格'
                   
                   WHEN 544268312 THEN '尊享'
  		   WHEN 545004624 THEN '尊享'
                   WHEN 544977296 THEN '尊享'
                   WHEN 544258744 THEN '尊享'
                   WHEN 686026112 THEN '尊享'
                   
                   WHEN 544265984 THEN '小规格'
  		   WHEN 545003360 THEN '小规格'
                   WHEN 544974120 THEN '小规格'
                   WHEN 543785912 THEN '小规格'
                   WHEN 686006576 THEN '小规格'
                   WHEN 544994704 THEN '小规格'
                   WHEN 511229112 THEN '小规格'
                   WHEN 644643792 THEN '小规格'
                   
                   WHEN 544270976 THEN '中规格'
                   WHEN 545078776 THEN '中规格'
                   WHEN 544988616 THEN '中规格'
                   WHEN 543789880 THEN '中规格'
                   WHEN 686009072 THEN '中规格'
                   WHEN 544997304 THEN '中规格'
                   WHEN 511232248 THEN '中规格'
                   WHEN 644646208 THEN '中规格'
   	ELSE 'more' END
	AS goods,
        (
        	CASE 
       		WHEN `liqui_time`>=1572192000 AND `liqui_time`<1572278400 THEN
            	'星期一'
                WHEN `liqui_time`>=1572278400 AND `liqui_time`<1572364800 THEN
            	'星期二'
                WHEN `liqui_time`>=1572364800 AND `liqui_time`<1572451200 THEN
            	'星期三'
                WHEN `liqui_time`>=1572451200 AND `liqui_time`<1572537600 THEN
            	'星期四'
                WHEN `liqui_time`>=1572537600 AND `liqui_time`<1572624000 THEN
            	'星期五'							
                WHEN `liqui_time`>=1572624000 AND `liqui_time`<1572710400 THEN
            	'星期六'
                WHEN `liqui_time`>=1572710400 AND `liqui_time`<1572796800 THEN
            	'星期日'
                END
        )  week
FROM `user_order` WHERE `status` =2  
HAVING goods LIKE '大规格' AND week LIKE '星期日' ；
 -- 用户数
SELECT distinct user_id,
	CASE 
	`goods_id` WHEN 544946952 THEN '大规格'
  		   WHEN 544991584 THEN '大规格'
                   WHEN 544261936 THEN '大规格'
                   WHEN 686027832 THEN '大规格'
                   WHEN 511236720 THEN '大规格'
                   WHEN 545002240 THEN '大规格'
                   WHEN 644647808 THEN '大规格'
                   
                   WHEN 544268312 THEN '尊享'
  		   WHEN 545004624 THEN '尊享'
                   WHEN 544977296 THEN '尊享'
                   WHEN 544258744 THEN '尊享'
                   WHEN 686026112 THEN '尊享'
                   
                   WHEN 544265984 THEN '小规格'
  		   WHEN 545003360 THEN '小规格'
                   WHEN 544974120 THEN '小规格'
                   WHEN 543785912 THEN '小规格'
                   WHEN 686006576 THEN '小规格'
                   WHEN 544994704 THEN '小规格'
                   WHEN 511229112 THEN '小规格'
                   WHEN 644643792 THEN '小规格'
                   
                   WHEN 544270976 THEN '中规格'
                   WHEN 545078776 THEN '中规格'
                   WHEN 544988616 THEN '中规格'
                   WHEN 543789880 THEN '中规格'
                   WHEN 686009072 THEN '中规格'
                   WHEN 544997304 THEN '中规格'
                   WHEN 511232248 THEN '中规格'
                   WHEN 644646208 THEN '中规格'
   	ELSE 'more' END
	AS goods,
        (
        	CASE 
       		WHEN `liqui_time`>=1572192000 AND `liqui_time`<1572278400 THEN
            	'星期一'
                WHEN `liqui_time`>=1572278400 AND `liqui_time`<1572364800 THEN
            	'星期二'
                WHEN `liqui_time`>=1572364800 AND `liqui_time`<1572451200 THEN
            	'星期三'
                WHEN `liqui_time`>=1572451200 AND `liqui_time`<1572537600 THEN
            	'星期四'
                WHEN `liqui_time`>=1572537600 AND `liqui_time`<1572624000 THEN
            	'星期五'
                WHEN `liqui_time`>=1572624000 AND `liqui_time`<1572710400 THEN
            	'星期六'
                WHEN `liqui_time`>=1572710400 AND `liqui_time`<1572796800 THEN
            	'星期日'
                END
        )  week
FROM `user_order` WHERE `status` =2  
HAVING goods LIKE '大规格' AND week LIKE '星期日' ；

SELECT u.mobile,u.nickname,uso.`user_id` , COUNT( uso.`user_id` ) AS number
FROM `user_saving_order` AS uso
LEFT JOIN user AS u ON u.id =  uso.`user_id`
WHERE uso.`add_time` >=1567267200
AND uso.`add_time` <1572537600
AND uso.`status` =1
AND uso.`app_id` =5
GROUP BY uso.`user_id`
ORDER BY number 