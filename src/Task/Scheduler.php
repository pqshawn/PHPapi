<?php
/**
 * Class Scheduler
 */
Class Scheduler
{
    /**
     * @var SplQueue
     */
    protected $taskQueue;
    /**
     * @var int
     */
    protected $tid = 0;

    /**
     * Scheduler constructor.
     */
    public function __construct()
    {
        /* 
         * 维护列队SplQueue
         */
        $this->taskQueue = new SplQueue();
    }

    /**
     * 增加一个任务
     *
     * @param Generator $task
     * @return int
     */
    public function addTask(Generator $task)
    {
        $tid = $this->tid;
        $task = new Task($tid, $task);
        $this->taskQueue->enqueue($task);
        $this->tid++;
        return $tid;
    }

    /**
     * 把任务进入队列
     *
     * @param Task $task
     */
    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    /**
     * 运行调度器
     */
    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            // 任务出队
            $task = $this->taskQueue->dequeue();
            $res = $task->run();

            if (!$task->isFinished()) {
                $this->schedule($task);
            }
        }
    }
}