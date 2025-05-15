# emlog-to-wordpress-exporter
Emlog 数据无损迁移至 WordPress 插件 | Export Emlog blog to WordPress (WXR/XML)

# Emlog To WordPress Exporter

**Emlog To WordPress Exporter** 是一个针对 Emlog 博客系统开发的插件，支持将博客文章、页面、分类、标签、评论和友情链接数据一键导出为 WordPress 官方支持的 WXR（WordPress eXtended RSS）格式的 XML 文件，从而实现博客数据的无损迁移。

## 功能特性

- **文章/页面迁移**：支持导出所有博客文章和独立页面数据。
- **分类与标签支持**：提取 Emlog 中的分类（Sorts）及标签（Tags），便于在 WordPress 中直接使用。
- **评论数据保留**：完整导出文章对应的评论信息（包括评论者姓名、邮箱、网址、IP 及评论时间）。
- **友情链接转化**：将友情链接导出为 WordPress 可识别的链接类型数据。
- **一键导出**：插件提供导出按钮，点击即可自动生成符合 WordPress 导入标准的 XML 文件。

## 使用方法

### 1. 安装插件

1. 下载本插件，并将解压后的文件夹 `emlog-to-wordpress-exporter` 上传至 Emlog 插件目录：  
   `content/plugins/emlog-to-wordpress-exporter/`

2. 登录 Emlog 后台，进入插件管理页面，启用 **Emlog To WordPress Exporter** 插件。

### 2. 导出数据

1. 在 Emlog 后台的插件设置页中，点击 **“导出 WordPress XML”** 按钮。
2. 系统将自动生成一个名为 `wordpress-export.xml` 的 XML 文件。
3. 下载保存该文件。

### 3. 导入至 WordPress

1. 登录 WordPress 后台，进入【工具 → 导入】菜单。
2. 找到 **WordPress** 导入器，并安装后运行。
3. 上传刚才导出的 `wordpress-export.xml` 文件。
4. 根据提示选择文章归属作者，并勾选 “下载附件”（若需要迁移图片等媒体文件）。
5. 完成导入操作，所有内容数据将无缝迁移到 WordPress。

## 预览截图

![Banner](assets/banner.png)

## 开发说明

本插件通过调用 Emlog 内置数据库接口读取各数据表中的信息，然后将数据格式化为符合 WordPress 导入器要求的 WXR（XML）格式。这样，无需手动复制即可实现 Emlog 到 WordPress 的全站数据迁移。

## 授权协议

本项目基于 [MIT License](LICENSE) 开源协议，欢迎大家 fork、修改并参与改进。

## 作者信息

- **作者**：xfsay
- **博客**：[https://xfsay.com](https://xfsay.com)

## 项目发布与维护

- **GitHub 仓库**： [https://github.com/xfsay/emlog-to-wordpress-exporter](https://github.com/xfsay/emlog-to-wordpress-exporter)
- **Issues**：欢迎提交问题反馈和建议！
- **Releases**：在 [Releases](https://github.com/xfsay/emlog-to-wordpress-exporter/releases) 页面下载打包好的 ZIP 文件。

## 标签

`emlog`, `wordpress`, `博客迁移`, `博客搬家`, `xml导出`, `php插件`, `数据迁移`
