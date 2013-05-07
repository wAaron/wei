缓存
====

Widget提供了多个PHP流行的后端缓存.这里有apc,redis,memcached等主流缓存,也有简单的文件缓存fileCache,
可用于简单数据分析的数据库缓存dbCache等等.所有缓存拥有一致的接口,可以随意替换.

## 使用案例

* 缓存数据库查询,接口调用等耗时较长的操作
* 将缓存作为计数器,记录按钮点击次数,网站访问次数等等
* 在不同的请求之间,共享数据

## 支持的缓存类型及比较

|           | Cache | Apc | DbCache | FileCache | Memecache | Memcached | Couchbase | Redis   | Bicache | 
|-----------|-------|-----|---------|-----------|-----------|-----------|-----------|---------|---------|
| 速度      | -     | 快  | 慢      | 慢        | 快        | 快        | 快        | 快      | -       |
| 持久化    | -     | ×   | √       | √         | ×         | ×         | √         | √       | -       |
| 分布式    | -     | ×   | √       | ×         | √         | √         | √         | √       | -       |

* 持久化:缓存是否因为相关服务重启而丢失数据
* `Cache`和`Bicache`为视配置而定

## 通用方法

在下面的方法中,`cache`表示缓存微件的名称,可以替换为其他任意缓存,如`redis`, `memcached`

#### cache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### cache($key)
获取指定名称的缓存

#### cache->set($key, $value, $expire = 0)
设置缓存的值,同`cache($key, $value, $expire = 0)`

#### cache->get($key)
获取缓存的值,同`cache($key)`

#### cache->remove($key)
移除一项缓存

#### cache->exists($key)
检查缓存是否存在

#### cache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### cache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### cache->increment($key, $offset = 1)
增大一项缓存的值

#### cache->decrement($key, $offset = 1)
减小一项缓存的值

#### cache->getMulti($keys)
批量获取缓存的值

#### cache->setMulti($values)
批量设置缓存的值