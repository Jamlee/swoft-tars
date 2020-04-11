## Swoft2与Tars结合操作步骤

### 1. 按照 tars 的要求建立文件目录结构

```
.
├── docker-compose.yml
├── README.md
├── src
└── tars
```

### 2. 在src中放入 swoft 的源码，使用下面命令创建
```
# 项目目录下
composer create-project swoft/swoft src
```

### 3. 在添加和改动的文件
```
# 添加的文件夹和文件
src/app/Tars/*

# 基于Swoft原始项目改动的文件
src/app/bean.php
src/app/Task/CrontTask.php
```
