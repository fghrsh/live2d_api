# Live2D API

Live2D 看板娘插件 (https://www.fghrsh.net/post/123.html) 上使用的后端 API

### 特性

- 原生 PHP 开发，无需伪静态，开箱即用
- 支持 模型、皮肤 的 顺序切换 和 随机切换
- 支持 单模型 单皮肤 切换、多组皮肤 递归穷举
- 支持 同分组 多个模型 或 多个路径 的 加载切换

## 使用

### 环境要求
- PHP 版本 >= 5.2
- 依赖 PHP 扩展：json

### 目录结构

```shell
│  model_list.json              // 模型列表
│
├─model                         // 模型路径
│  └─GroupName                  // 模组分组
│      └─ModelName              // 模型名称
│
├─add                           // 更新皮肤列表
├─get                           // 获取模型配置
├─rand                          // 随机切换模型
├─rand_textures                 // 随机切换皮肤
├─switch                        // 顺序切换模型
├─switch_textures               // 顺序切换皮肤
└─tools
        modelList.php           // 列出模型列表
        modelTextures.php       // 列出皮肤列表
        name-to-lower.php       // 文件名格式化
```

### 添加模型

- 单模型 单皮肤 切换
    - 单次加载只输出一个皮肤
    - 皮肤放在 `textures` 文件夹，自动识别

```shell
│  index.json
│  model.moc
│  textures.cache       // 皮肤列表缓存，自动生成
│
├─motions
│      idle_01.mtn
│      idle_02.mtn
│      idle_03.mtn
│
└─textures
        default-costume.png
        school-costume.png
        winter-costume.png
```

- 单模型 多组皮肤 递归穷举
    - 多组皮肤 组合模型、穷举组合
    - 皮肤文件夹按 `texture_XX` 命名
    - 添加 `textures_order.json` 列出组合
```shell
│  index.json
│  model.moc
│  textures.cache
│  textures_order.json
│
├─motions
│      idle_01.mtn
│      idle_02.mtn
│      idle_03.mtn
│
├─texture_00
│      00.png
│
├─texture_01
│      00.png
│      01.png
│      02.png
│
├─texture_02
│      00.png
│      01.png
│      02.png
│
└─texture_03
       00.png
       01.png
```

textures_order.json

```json
[
    ["texture_00"],
    ["texture_01","texture_02"],
    ["texture_03"]
]
```

textures.cache

```json
[
    ["texture_00/00.png","texture_01/00.png","texture_02/00.png","texture_03/00.png"],
    ["texture_00/00.png","texture_01/00.png","texture_02/00.png","texture_03/01.png"],
    ["texture_00/00.png","texture_01/01.png","texture_02/01.png","texture_03/00.png"],
    ["texture_00/00.png","texture_01/01.png","texture_02/01.png","texture_03/01.png"],
    ["texture_00/00.png","texture_01/02.png","texture_02/02.png","texture_03/00.png"],
    ["texture_00/00.png","texture_01/02.png","texture_02/02.png","texture_03/01.png"]
]
```

- 同分组 多个模型 或 多个路径 切换
    - 修改 `model_list.json` 添加多个模型

```shell
│
├─model
│  ├─Group1
│  │  ├─Model1
│  │  │      index.json
│  │  │
│  │  └─Model2
│  │          index.json
│  │
│  ├─Group2
│  │  └─Model1
│  │          index.json
│  │
│  └─GroupName
│     └─ModelName
│          │  index.json
│          │  model.moc
│          │
│          ├─motions
│          └─textures
│
```

model_list.json
```json
{
    "models": [
        "GroupName/ModelName",
        [
            "Group1/Model1",
            "Group1/Model2",
            "Group2/Model1"
        ]
    ],
    "messages": [
        "Example 1",
        "Example 2"
    ]
}
```

### 接口用法
- `/add/` - 检测 新增皮肤 并更新 缓存列表
- `/get/?id=1-23` 获取 分组 1 的 第 23 号 皮肤
- `/rand/?id=1` 根据 上一分组 随机切换
- `/switch/?id=1` 根据 上一分组 顺序切换
- `/rand_textures/?id=1-23` 根据 上一皮肤 随机切换 同分组其他皮肤
- `/switch_textures/?id=1-23` 根据 上一皮肤 顺序切换 同分组其他皮肤

## 版权声明

> (>▽<) 都看到这了，点个 Star 吧 ~

**API 内所有模型 版权均属于原作者，仅供研究学习，不得用于商业用途**  

MIT © FGHRSH
