<?php
namespace PhpApi\Standard\Filter;


/**
 * Filter
 * 

 request一组复杂类型参数
 Array
(
    [os_version] => v1.0
    [details] => Array
        (
            [os_name_test] => 这是测试中文
            [os_details] => Array
                (
                    [os_version] => 322fasdfa
                    [os_sim] => 123
                    [array_test] => Array
                        (
                            [0] => Array
                                (
                                    [tkey] => tvalue
                                    [tkey2] => Array
                                        (
                                            [0] => 1
                                            [1] => 2
                                            [2] => 3
                                        )

                                )

                            [1] => Array
                                (
                                    [0] => 0
                                    [1] => Array
                                        (
                                            [0] => 9
                                            [1] => Array
                                                (
                                                    [0] => 3
                                                )

                                        )

                                )

                            [2] => 3value
                        )

                )

            [phone_version] => 3kkaia39393
            [description] => 描述
        )

    [sign] => {sign}
)



对照复杂类型定义的规则，往往规则没有这么复杂，只需定义json的第一层键值对就好了。
Array
(
    [os_version] => Array
        (
            [require] => 1
            [type] => string
            [size] => 4
            [format] => version
            [regex] => /^v[\d+]\.[\d+].[\d+]$/
            [data] => v1.0
        )

    [details] => Array
        (
            [os_name_test] => Array
                (
                    [require] => 1
                    [type] => string
                    [size] => 20
                    [format] => version
                    [data] => 这是测试中文
                )

            [os_details] => Array
                (
                    [os_version] => Array
                        (
                            [require] => 1
                            [type] => string
                            [size] => 11
                            [format] => version
                            [data] => 322fasdfa
                        )

                    [os_sim] => Array
                        (
                            [require] => 
                            [type] => int
                            [size] => 11
                            [format] => 
                            [data] => 123
                        )

                    [array_test] => Array
                        (
                            [array_test/0/tkey] => Array
                                (
                                    [require] => 1
                                    [type] => string
                                    [size] => 11
                                    [format] => version
                                    [regex] => /^v[\d+]\.[\d+].[\d+]$/
                                    [data] => tvalue
                                )

                            [array_test/0/tkey2/0] => Array
                                (
                                    [format] => numletter
                                    [regex] => 
                                    [data] => 1
                                )

                            [array_test/0/tkey2/1] => Array
                                (
                                    [format] => numletter
                                    [regex] => 
                                    [data] => 2
                                )

                            [array_test/0/tkey2/2] => Array
                                (
                                    [format] => numletter
                                    [regex] => 
                                    [data] => 3
                                )

                            [array_test/1/tkey] => Array
                                (
                                    [require] => 1
                                    [type] => string
                                    [size] => 11
                                    [format] => version
                                    [regex] => /^v[\d+]\.[\d+].[\d+]$/
                                    [data] => tvalue
                                )

                            [array_test/2] => Array
                                (
                                    [format] => int
                                    [regex] => 
                                    [data] => 3value
                                )

                        )

                )

            [phone_version] => Array
                (
                    [require] => 1
                    [type] => string
                    [size] => 11
                    [format] => version
                    [data] => 3kkaia39393
                )

        )

)


 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 interface FilterInterface {

    /**
     * 获取类型
     * 
     * @param string $key
     * @return mixe\d 
     */
    public function get($key);

    /**
     * 设置类型
     * 
     * @param string $key
     * @param string $value
     * @param string $expire
     * @return boolea/n
     */
    public function set($key, $value);

    /**
     * 普通判断
     * @param string $dataName 数据名称
     * @param mixed $data 数据值
     * @param mixed $typeData 类型值， 如require = 1, 这个1就是类型值，require是类型
     */
    public function check($dataName, $data, $typeData);

    /**
     * 返回状态
     */
    public function retStatus();

    /**
     * 返回消息
     */
    public function retErrorMessageTpl($parameterName);

    public function checkTypeData($typeData = '');

    /**
     * 默认类型值-检查
     * 可能类型值处理比较简单，不用每数据类型值单独建立方法，统一判断即可，可重写此方法
     */
    public function checkDefaultTypeData($typeData, $data);
 }