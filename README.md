Install
=====
```
git clone https://github.com/yanpeipan/giter.git
cd giter
git submodule init
git submodule update
```
# git仓库server搭建 #
## 环境介绍 ##
- 系统环境：Ubuntu 10.10
- 软件环境：git version 1.7.1 **;** Apache/2.2.16
- 说明：Git访问方式：基于http的基本验证（非SSL）
## 安装步骤 ##
1. 安装软件

    `apt-get install -y git-core git apache2`

2. 仓库目录设置

    `mkdir -p /scm/git/{repository,auth} && chown -R www-data. /scm/`

3. Apache配置

		Alias /git "/scm/git/repository"
		<Directory "/scm/git/repository/">
			Dav On
			Options +Indexes +FollowSymLinks
			Deny from all
			Order Allow,Deny
			AuthType Basic
			AuthName "Git"
			AuthUserFile "/scm/git/auth/git.user"
			AuthGroupFile "/scm/git/auth/git.group"
		</Directory>

		<Directory "/scm/git/repository/test.git/">
			Allow from all
			Order Allow,Deny
			<Limit GET>
				Require group hgz-read
			</Limit>
			<Limit GET PUT POST DELETE PROPPATCH MKCOL COPY MOVE LOCK UNLOCK>
				Require group hgz-write
			</Limit>
		</Directory>
## 测试服务器是否可用 ##
1. 创建测试项目

    `cd /scm/git/repository && git init --bare test.git && cd test.git && git update-server-info && cp ./hooks/post-update.sample ./hooks/post-update && chown -R www-data. ../test.git`
2. 在其他机器上git clone

    `git clone http://gitserver/git/test.git`
