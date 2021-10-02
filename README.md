# README

## アプリ名
cafe_owl_bbq

## 概要
BBQの予約サイトです。  
ユーザーは、一般ユーザーと管理ユーザーの2種類存在し、BBQの予約は一般ユーザーが非会員で利用可能です。  
一般ユーザーの予約後、予約を管理できるのは管理ユーザーとなっており、管理ユーザーの機能はログインしないと使用できません。  

## 使用方法
### 一般ユーザー
BBQの予約はログインなしで行えます。  
トップページ(index.php)のheaderにある「ご予約」から開始できます。  
予約の際、メールアドレスをご自身のローカル環境用のメールアドレスで登録すると予約完了メールが飛びます。  

### 管理ユーザー
管理ユーザー機能は、トップページ(index.php)のfooterにある「admin」からログイン後に開始できます。  
管理ユーザーは、責任者と従業員で権限が2種類存在し、責任者は管理ユーザー機能の全てを使用できますが、従業員は使用制限があります。  
使用可能な機能は以下の通りです。  

**①責任者・従業員共通でできること**  
- アカウント発⾏及びログイン後、予約⼀覧・予約詳細の閲覧  
- 予約状況の変更(予約中・来店済・キャンセルのいずれか)  
┗キャンセルする予約のメールアドレスをご自身のローカル環境用のメールアドレスで登録するとキャンセル完了メールが飛びます。  
- ⾃⼰アカウントの編集・更新  
┗名前・メールアドレス・パスワードが編集でき、いずれも任意  

**②責任者のみできること**  
- アカウント登録済みの管理ユーザー⼀覧閲覧  
- 管理ユーザーの権限(役名)変更(責任者・従業員のいずれか)  
- 管理ユーザーの削除  
- 管理ユーザーのアカウント新規登録  

**③その他、パスワード紛失者**  
- 登録済みメールアドレスからURLを受け取り、パスワードの再設定  
┗パスワードを再設定するアカウントのメールアドレスをご自身のローカル環境用のメールアドレスで登録すると使用できます。

`《初期ログイン用アカウント》`  
`▪メールアドレス：tanaka@test.ne.jp`  
`▪パスワード：tanaka0101`  
`▪権限：責任者`

## 開発環境
MAMP・MySQL・PHP・JavaScript・jQuery・HTML・CSS

## DB

### ordersテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|adult|int|NOT NULL|
|schoolchildren|int|NOT NULL|
|preschooler|int|NOT NULL|
|date|date|NOT NULL|
|time|varchar(20)|NOT NULL|
|plan|varchar(3)|NOT NULL|
|total_price|int|NOT NULL|
|name|varchar(20)|NOT NULL|
|name_kana|varchar(40)|NOT NULL|
|address_prefectures|varchar(5)|NOT NULL|
|address_city|varchar(50)|NOT NULL|
|address_subsequent|varchar(50)|NOT NULL|
|phone|varchar(11)|NOT NULL|
|mail|varchar(100)|NOT NULL|
|group_name|varchar(50)||
|phase|int|NOT NULL, DEFAULT 0|
|created_at|timestamp|NOT NULL|

### product_ordersテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|order_id|int|NOT NULL|
|name|varchar(50)|NOT NULL|
|price|int|NOT NULL|
|quantity|int|NOT NULL|
|category|int|NOT NULL|

### option_ordersテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|order_id|int|NOT NULL|
|name|varchar(50)|NOT NULL|
|price|int|NOT NULL|
|quantity|int|NOT NULL|

### productsテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|name|varchar(50)|NOT NULL|
|price|int|NOT NULL|
|category|int|NOT NULL|

### optionsテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|name|varchar(50)|NOT NULL|
|price|int|NOT NULL|

### adminsテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|name|varchar(20)|NOT NULL|
|email|varchar(100)|NOT NULL|
|password|varchar(100)|NOT NULL|
|role|int|NOT NULL|

### tokensテーブル

|Column|Type|Option|
|------|----|------|
|id|int|NOT NULL, PRIMARY KEY|
|admin_id|int|NOT NULL|
|token|varchar(100)|NOT NULL|
|datetime|datetime|NOT NULL|

お使いのphpMyAdminに上のデータベースを作り、入っているDB.sqlをインポートしていただければご使用いただけます。
