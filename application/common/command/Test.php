<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/11/13
 * Time: 上午15:40
 */

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('Test')->setDescription('注意要区分大小写');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln("TestCommand:注意要区分大小写");
    }
}