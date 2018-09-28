# PHPapi
一个基于php语言的api框架-PHPapi（非芭比）

## 1）说明
PHPapi是由得道创科工作室开发，基于BSD-2-Clause协议，用户可以放心免费使用，分发或二次开发。
如果想更深入了解本框架，我们并行推出了商业版，得创竭诚为您服务，欢迎联系。

## 2）API框架设计
2-1）基础模板来自sparrowPHP
2-2）命名空间
更新sparrowPHP用命名空间，autoload注意命名空间类名的加载。
2-3）项目结构
2-3-1）核心的代码我们把它打包成composer，现代主流语言框架都会这么做，如java的maven,node的npm或python的pip，这是现代php必须进化的东西。
核心基本结构是：

这样的别的项目需要，可以直接composer require. 当然我们也可以直接用composer create-project,用2-3-2）里的完整项目结构，直接在上面二次开发即可。
2-3-2）代入核心代码，封装成整个项目基本结构是：



2-3-3）各层的意义
/public 
我们的业务代码主要放在这里，也可以在public新建多个文件夹，每个文件夹下一个index.php,配合nginx映射多个项目。如/public/news/index.php  /public/video/index.php. 对应的，也应在application里新建多个app。
/application
可以在此文件夹里新建多个app文件夹。如/application/news  , /application/video. 每个app里要有多个文件夹，即要分层管理app业务代码。
/application/common 
多个app可能的工具类方法可以放在这里
application每个app的分层结构
包括controller, service, model, util自定义工具类



2-4）需要加载的文件
入口文件要引入composer，语言，调试模式选择等。现在假设我们用composer要把我们的核心API代码封闭成包，我们就要考虑什么时候可以把自编的composer包引进来，娴接住在public（web服务器指向的根目录）index.php。 我们在编写sparrowPHP是采用顺序开发的方法，从index出发，模拟一次正常的操作，把关联一个个关系一层层建立起来。

这里因为是把核心代码要打成composer包，采用引进方式。而index是独立于这个composer空间的，把他们关联起来，就要摈弃原先的开发模式。采用模块式开发，把一次完整的典型的请求和响应，需要的所有的类和方法，分派到各个文件中去，我们先把可以实现的框架属性骨架搭建起来，因为按照TDD开发模式的要求，我们以单元测试驱动，这样打散各个类，功能，和类之间关联很有较好的把握。

基础加载的模块有：
 a.依赖注入 b.请求 c.响应 d.router类 e.主启动类（kernel）f.异常 g.校验
因为依赖注入这种编码模式，利于维护，减少代码量，同时提高性能等优点，因此，上面几个模块的类定义采用注入的方式，通俗点讲，就是单例模式的应用，结合__set，__get魔术变量，实现懒加载的功能。哪里需要哪里注入，不在多个被依赖类里滥用实例化，

2-5）其他模块
在app需要时再加载的模块，也有的是入口时加载的的依赖类，这些模块也归纳下：
a.配置，b.日志  c.语言包  d.Dao类，e.过滤类，f.orm-model层 g.cache类  h.控制层 i.debug j.service领域层 k.时区

语言包，错误码，时区等 可以合在配置里，由配置统一管理。

2-6）框架骨架搭建
声明所有接口类，把规范制定，通常这种接口定义类在小型项目中被应用的很少，如果项目体积达到一定量的话，这方面的架构显得格外重要，因为技术管理者不可能细化到每个类和方法，只在于骨架的规范。我们设置一目了然的骨架规范，让技术人员更好的理解整个框架，可以更多人，更好的参与进来。
src/Standard的结构如下：

2-7）采用TDD测试驱动开发+领域模型开发
Unit Test Driven Development，UTDD：在编码之前写测试脚本，称为单元测试驱动开发
Acceptance Test Driven Development，ATDD：业务层次方面，在需求分析时就确定需求（如用户故事）验收标准，称为验收测试驱动开发
由此可见，PHPapi基于测试驱动的框架，2-4）~2-6）有我们需要的基本框架涵盖的基本功能，我们可以先写测试，而后写代码，再自我测验。而application里的service层即我们的领域层，主要处理业务逻辑，是领域模型开发的主战场，我们的核心代码用到很少。
再补充一下这对组合模型开发的好处，设计者往往不能太在技术上死磕，测试驱动，先引导做正确的事，再将你的关注引导到领域业务上，最后技术的选型，采用什么样的分布式，缓存，库表设计等等迎刃而解。当然这样也有点缺点，耗费在测试上，而且往往公司没有专门的测试开发工程师，只能把压力转向开发人员，但如果项目的规模和预期都比较多，摊子比较大，面对今后的重构等方面问题，还是用这种方式。
PHPApi立足于简美代码，长久支持，可扩可移，迭代式开发，渐布式大型项目，所以采用TDD测试驱动开发

2-8）phpunit测试骨架搭建
搭建一个类同src下的项目框架，存在tests文件下，起名" 原先的文件名_Test.php "。我们往往不是一次性用生成工具直接生成，而是按照一个领域模型的要求，先想想我们要开发具有什么样功能的框架，在这个意愿上，建立我们需要的类。当然，我们在前期有很丰富的经验，知道我们的基本框架需求，我们先按照2-6)框架骨架里的文件，一一对应个"xxxTest.php"，方便我们对每个类和功能进行测试。

下面我们结合测试，对每个主要类别或模块进行简单描述。



2-9）kernel主启动类
在操作系统都有一个这样的kernel启动程序，其目的是在Bootloader之后加载内存，挂载硬盘，挂载rootfs系统资源，使用户操作文件状态准备就绪，之后文件状态的关联都跟它有关系。
我们设置三大工厂入口，routerFactory,requestFactory, responseFactory, 这三个模块，可以由开发者在config里配置，或者使用默认，带到一个特定的生产类进行生产，生产类可以任由扩展。灵活扩展的router，request，response的工厂，  request，response 放在controller模块初始化里
建立kernel单例，其实与Di依赖注入类大同小异，不同点是，Di实现了懒加载，还对所有加载的对象统一到一个静态实例管理。
不在用户的项目里引进多余的初始代码，定义与用户项目无关的东西，与用户代码的耦合度达到最小

2-10）日志模块
2-10-1）框架系统日志
日志实时监控系统类，对象，方法和参数变量（调试时非常实用），每个类方法都可以定制业务上的数据，并返回到日志。
具有观察者模式的中间件
































2-11）router模块
Router支持无限级
支持制定灵活的规范的错误码

2-12）controller模块
我们在controller设置了日志

2-13）response模块
response工厂模式，按需扩展
支持自定义CONTENT-TYPE，自定义加密压缩传输

2-14）service领域服务模型
进入AI时代后，人们的生产和生活节奏更加的快速，产品不断迭代，idea在井喷，业务变得大到臃肿小到琐碎。我们不断把idea累加，而忘了问题的起始点，导致我们口口声声的强调产品体验实是最后一块遮羞布。我们要解决的是什么问题，即问题空间，我们把公司的战略产品应用到实际当中，要干什么。怎么样解决问题，即解决问题空间，怎么样把产品构想转化成可操作的技术架构，乃至每个子系统充当的螺丝。这才是我们的初衷。做了很多曲折，才发现给用户带来的不是更多选择，而是添麻烦。产品不是一加一的层叠，不是眼花缭乱的功能，而是系统核心真正实现的价值，带来的便利。可以发现市面上很少有战略上的失误，每种产品都或多或少的养活很多家庭，存在即是合理，生产决定价值（消费）。大多数的会是战术上的话题。根据战略模型划分的核心领域，子领域，和界限上下文。我们抽象出实体关系来，即战术建模（解决问题建立的实体关系，ER图，架构图例等），之后开展业务时，不可能脱离这个战术模型，除非是开发新产品。我们在代码框架里也要突显这种模型来。在某个已有A子领域扩充，或增强功能。某个不存在B子领域，则新添加。在技术架构层面，都在明确一件核心的事情，副带辐射几个子领域。而在产品层面，产品的意愿和技术的框架无缝对接。
这样我们的API都不会乱七八糟，几经转手后，开发人员更好的衔接，用非技术角度看产品的实质模块，是什么，怎么解决，一目了然。既规避了新产品人员不知道技术门槛，重新掉在原先的大坑，也能接上原先的story，而急于表现自己的产品功底，而提出不符合实际的需求，又规范了新老开发人员的设计编码，不是上来就CURD，拆东墙补西墙。而是了解产品的初衷，了解框架里的子领域解决的问题，核心领域的实现，了解技术规范，和承接从一诞生就存在产品基因里的有条不紊。

领域服务（story的拆分）封装成许多具体的装饰角色。
每个领域服务中，即story开始到结束，我们以每个子域做什么，分成几步，每一步都可以装饰在基本构件上。最后得到我们要的功能。
2-15）异常模块
灵活的自定义异常处理
2-16）自定义错误警告

2-17）request模块
request模块采用[反射+注入+迭代模式]，方便业务层扩展极其复杂的依赖以及方便测试和优化代码，这种组合，在大型复杂业务中，特别是对迭代到一定程度的代码是非常受用的，让复杂的数据组件间的依赖关系变得轻松。
商业版PHPapi我们把它处理成公共类库，因为这种模式还是要在大型复杂业务中更显得非常积极有效，方便处理复杂业务和回调，改变开发者的编写习惯，提高工作效率。

2-18）安全模块
两种选择，对应不同配置和基类，可以选择数据安全级别非常高：HTTPS传输+令牌+非对称加密(server生成公钥私钥，告知端公钥)
也可以选择，数据安全级别较高：HTTPS传输+令牌+对称加密(端生成deskey登陆告知server)