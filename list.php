<?php

    class Queue
    {
        private $head; //队头
        private $tail; //队尾
        private $queue = [0=>'此位禁用']; // 存储队列 array('0'=>'队尾')
        private $maxsize; //最大数

        // 构造函数
        public function __construct($size)
        {
            $this->initQ($size);
        }

        // 初始化队列
        private function initQ($size)
        {
            $this->head = 0;
            $this->tail = 0;
            $this->maxsize = $size;
            echo '初始化队列：<br>';
            print_r($this->queue);
        }

        // 判断队空
        public function QIsEmpty()
        {
            return $this->head == $this->tail;
        }

        // 判断队满
        public function QIsFull()
        {
            return ($this->head - $this->tail) === $this->maxsize;
        }

        // 入队
        public function InQ($param)
        {
            if ($this->QIsFull()) {

                echo $param . ': 队列已满，请等待！<br>';
            } else {

                $this->head++;

                // 入队的时候，把队列一次向前挤
                for ($i = $this->head; $i > $this->tail; $i--) {
                    $this->queue[$i] = $this->queue[$i - 1];
                }

                // 队尾是新数据
                $this->queue[$this->tail + 1] = $param;
                echo $param . ': 入队成功！[ 排在第 ' . $this->head . ' 位 ]<br>';
                print_r($this->queue);
            }
        }

        // 出队
        public function OutQ(){

            if($this->QIsEmpty()){

                echo '警告: 队列已空！<br>';
            } else{

                echo $this->queue[$this->head] . ': 出队成功！<br>';
                unset($this->queue[$this->head]);
                $this->head--;
                print_r($this->queue);
            }
        }
    }


    echo '<pre>';
    $queue=new Queue(3);
    echo '<hr>';
    $queue->InQ('唐僧');
    echo '<hr>';
    $queue->InQ('孙悟空');
    echo '<hr>';
    $queue->InQ('猪八戒');
    echo '<hr>';
    $queue->InQ('沙和尚');
    echo '<hr>';
    $queue->OutQ();
    echo '<hr>';
    $queue->InQ('沙和尚');
    echo '<hr>';
    $queue->OutQ();
    echo '<hr>';
    $queue->OutQ();
    echo '<hr>';
    $queue->OutQ();
    echo '<hr>';
    $queue->OutQ();
    echo '<hr>';
    $queue->OutQ();

