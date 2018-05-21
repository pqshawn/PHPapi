<?php
namespace PhpApi;

use PhpApi\Standard\Service\ServiceInterface;

/**
 * service
 * 这里做为顶层基本抽象构件使用
 * 请调用后做实际实现
 * 
 * 主要处理业务上的逻辑，称为领域服务模块
 * 我们用领域的思维方式，把系统拆分各个子领域，核心领域
 * 在此基础上，我们还可以拆分每个产品idea的story,全部与技术领域模型挂钩，这样既统一利于产品的迭代和保持初衷
 * 又规范了开发设计规范，提高开发人员产品意识
 * 
 * 复杂子领域，与其他子领域重度交织的，即考虑从架构上规避，如果非得重度互相耦合，
 * 可考虑装饰器模式，来规范它们的调用
 * 
 * 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
*/

abstract class Service implements ServiceInterface {

    /**
     * 构件融合
     */
    protected function component() {

    }
}