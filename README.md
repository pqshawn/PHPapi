# PHPapi
[![PHPapi-version](https://img.shields.io/badge/version-1.0-blue)]() [![Build Status](https://travis-ci.org/pqshawn/PHPapi.svg?branch=master)](https://travis-ci.org/pqshawn/PHPapi)

一个基于php语言的api框架-PHPapi（非芭比）

## 安装说明 [![The composer for PHPapi](https://img.shields.io/badge/composer-1.9.3-blue)](https://packagist.org/packages/ldos/phpapi)

    composer安装：

    composer require ldos/phpapi

这里有已经封装好的示例项目，可直接下载使用[![PHPapi-Framework](https://img.shields.io/badge/version-1.0-blue)](https://packagist.org/packages/ldos1/phpapi-framework)：

    git 下载：

    git clone https://github.com/pqshawn/PHPapi-Framework

    或者

    composer安装： 

    composer create-project ldos/phpapi-framework



## 1）说明
PHPapi是由得道创科工作室开发，基于BSD-2-Clause协议，用户可以放心免费使用，分发或二次开发。免费版本的设计使用文档，后期同步更新出来，以下是缩略的部分信息，方便开发者使用。
如果想更深入了解本框架，我们并行推出了商业版，得创竭诚为您服务，欢迎联系。

## 2）API框架设计
### 2-1）sparrowPHP
### 2-2）命名空间
### 2-3）项目结构
#### 2-3-1）composer
#### 2-3-2）整个项目基本结构

#### 2-3-3）各层的意义

### 2-4）需要加载的文件
基础加载的模块有：
 a.注入 b.请求 c.响应 d.router类 e.主启动类（kernel）f.异常 g.校验
因为依赖注入这种编码模式，利于维护，减少代码量，同时提高性能等优点，因此，上面几个模块的类定义采用注入的方式，通俗点讲，就是单例模式的应用，结合__set，__get魔术变量，实现懒加载的功能。哪里需要哪里注入，不在多个被依赖类里滥用实例化，

### 2-5）其他模块
在app需要时再加载的模块，也有的是入口时加载的的依赖类，这些模块也归纳下：
a.配置，b.日志  c.语言包  d.Dao类，e.过滤类，f.orm-model层 g.cache类  h.控制层 i.debug j.service领域层 k.时区

语言包，错误码，时区等 可以合在配置里，由配置统一管理。

### 2-6）框架骨架搭建
用到的设计模式，适配器、工厂、单例、命令链、策略、迭代、观察者、装饰器等等。
声明所有接口类，把规范制定，通常这种接口定义类在小型项目中被应用的很少，如果项目体积达到一定量的话，这方面的架构显得格外重要，因为技术管理者不可能细化到每个类和方法，只在于骨架的规范。我们设置一目了然的骨架规范，让技术人员更好的理解整个框架，可以更多人，更好的参与进来。
src/Standard的结构如下：

### 2-7）采用TDD测试驱动开发+领域模型开发
..........好处，设计者往往不能太在技术上死磕，测试驱动，先引导做正确的事，再将你的关注引导到领域业务上，最后技术的选型，采用什么样的分布式，缓存，库表设计等等迎刃而解。当然这样也有点缺点，耗费在测试上，而且往往公司没有专门的测试开发工程师，只能把压力转向开发人员，但如果项目的规模和预期都比较多，摊子比较大，面对今后的重构等方面问题，还是用这种方式。
PHPApi立足于简美代码，长久支持，可扩可移，迭代式开发，渐布式大型项目，所以采用TDD测试驱动开发........

### 2-8）phpunit测试骨架搭建
搭建一个类同src下的项目框架，存在tests文件下，起名" 原先的文件名_Test.php "。我们往往不是一次性用生成工具直接生成，而是按照一个领域模型的要求，先想想我们要开发具有什么样功能的框架，在这个意愿上，建立我们需要的类。当然，我们在前期有很丰富的经验，知道我们的基本框架需求，我们先按照2-6)框架骨架里的文件，一一对应个"xxxTest.php"，方便我们对每个类和功能进行测试。
下面我们结合测试，对每个主要类别或模块进行简单描述
.......................

### 2-9）kernel主启动类
注意三大工厂类.......

### 2-10）日志模块
#### 2-10-1）框架系统日志
日志实时监控系统类，对象，方法和参数变量（调试时非常实用），每个类方法都可以定制业务上的数据，并返回到日志。
具有观察者模式的中间件

### 2-11）router模块
Router支持无限级
支持制定灵活的规范的错误码

### 2-12）controller模块
controller日志排查

### 2-13）response模块
response工厂模式，按需扩展
支持自定义CONTENT-TYPE，自定义加密压缩传输

### 2-14）service领域服务模型
每个领域服务中，即story开始到结束，我们以每个子域做什么，分成几步，每一步都可以装饰在基本构件上。最后得到我们要的功能。
### 2-15）异常模块
灵活的自定义异常处理
### 2-16）自定义错误警告
### 2-17）request模块
request模块采用[反射+注入+迭代模式]，方便业务层扩展极其复杂的依赖以及方便测试和优化代码，这种组合，在大型复杂业务中，特别是对迭代到一定程度的代码是非常受用的，让复杂的数据组件间的依赖关系变得轻松。
商业版PHPapi我们把它处理成公共类库，因为这种模式还是要在大型复杂业务中更显得非常积极有效，方便处理复杂业务和回调，改变开发者的编写习惯，提高工作效率。
### 2-18）安全模块
两种选择，对应不同配置和基类，可以选择数据安全级别非常高：HTTPS传输+令牌+非对称加密(server生成公钥私钥，告知端公钥)
也可以选择，数据安全级别较高：HTTPS传输+令牌+对称加密(端生成deskey登陆告知server),
Api提供签名认证，客户端可以根据需要对每个返回指令进行签名认证
### 2-19) 性能测试
![avatar](http://47.103.102.63:8088/temp/oss/callgraph.png)
