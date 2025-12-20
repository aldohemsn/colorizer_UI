# CoreNLP Docker Server

这个目录包含用于在本地运行 Stanford CoreNLP 服务器的 Docker 配置。

## 支持的语言
- 英语 (English)
- 西班牙语 (Spanish)

## 快速开始

### 使用 Docker Compose（推荐）

```bash
cd corenlp-docker
docker-compose up -d
```

### 使用 Docker 命令

```bash
cd corenlp-docker

# 构建镜像
docker build -t corenlp-server .

# 运行容器
docker run -d --name corenlp-server -p 9000:9000 corenlp-server
```

## 测试服务

等待服务启动后（约 30-60 秒），可以测试：

```bash
# 检查服务状态
curl http://localhost:9000/ready

# 测试英语分析
curl --data 'The quick brown fox jumped over the lazy dog.' \
  'http://localhost:9000/?properties={"annotators":"tokenize,ssplit,pos","outputFormat":"json"}'

# 测试西班牙语分析
curl --data 'El rápido zorro marrón saltó sobre el perro perezoso.' \
  'http://localhost:9000/?properties={"annotators":"tokenize,ssplit,pos","pipelineLanguage":"es","outputFormat":"json"}'
```

## 停止服务

```bash
# 使用 Docker Compose
docker-compose down

# 或使用 Docker 命令
docker stop corenlp-server
docker rm corenlp-server
```

## 配置说明

- **端口**: 9000
- **内存**: 2GB（可在 docker-compose.yml 中调整）
- **预加载模块**: tokenize, ssplit, pos
- **超时时间**: 60 秒

## 在 colorizer_UI 中使用

设置环境变量：

```bash
CORENLP_URL=http://localhost:9000
CORENLP_USER=  # 如果不需要认证可留空
CORENLP_PASSWORD=  # 如果不需要认证可留空
```
