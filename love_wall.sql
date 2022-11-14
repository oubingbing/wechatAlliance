/*
 Navicat Premium Data Transfer

 Source Server         : 华为
 Source Server Type    : MySQL
 Source Server Version : 50723
 Source Host           : 139.159.243.207:3306
 Source Schema         : love_wall

 Target Server Type    : MySQL
 Target Server Version : 50723
 File Encoding         : 65001

 Date: 10/11/2022 12:47:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `access_tokens`;
CREATE TABLE `access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) NOT NULL COMMENT 'app_id',
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'access_token',
  `expired_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '过期时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `access_tokens_app_id_index`(`app_id`) USING BTREE,
  INDEX `access_tokens_expired_at_index`(`expired_at`) USING BTREE,
  INDEX `access_tokens_created_at_index`(`created_at`) USING BTREE,
  INDEX `access_tokens_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 323 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_apps
-- ----------------------------
DROP TABLE IF EXISTS `admin_apps`;
CREATE TABLE `admin_apps`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) NOT NULL COMMENT '管理员id',
  `app_id` bigint(20) NOT NULL COMMENT '微信小程序id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_apps_admin_id_index`(`admin_id`) USING BTREE,
  INDEX `admin_apps_admin_app_id_index`(`app_id`) USING BTREE,
  INDEX `admin_apps_created_at_index`(`created_at`) USING BTREE,
  INDEX `admin_apps_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 781 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户微信昵称',
  `avatar` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预留账号密码',
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预留手机号码字段',
  `active_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号激活码',
  `token_expire` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '激活码失效时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户状态，0未激活，1=已激活',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `remember_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admins_email_unique`(`email`) USING BTREE,
  INDEX `admins_mobile_index`(`mobile`) USING BTREE,
  INDEX `admins_created_at_index`(`created_at`) USING BTREE,
  INDEX `admins_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1338 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for apps
-- ----------------------------
DROP TABLE IF EXISTS `apps`;
CREATE TABLE `apps`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '小程序的名字',
  `app_key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '小程序的APP key',
  `app_secret` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '小程序的密钥',
  `alliance_key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联盟给的身份标识，接口需要传递这个key',
  `domain` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '小程序的接口域名',
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人手机号码',
  `college_id` bigint(20) NULL DEFAULT NULL COMMENT '学校',
  `status` tinyint(4) NOT NULL DEFAULT 2 COMMENT '小程序内容安全，1=开启，2=关闭',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `service_id` bigint(20) NULL DEFAULT NULL COMMENT '客服id',
  `attachments` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '小程序的相关图片',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `apps_app_key_index`(`app_key`) USING BTREE,
  INDEX `apps_alliance_key_index`(`alliance_key`) USING BTREE,
  INDEX `apps_college_id_index`(`college_id`) USING BTREE,
  INDEX `apps_created_at_index`(`created_at`) USING BTREE,
  INDEX `apps_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 785 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for black_list
-- ----------------------------
DROP TABLE IF EXISTS `black_list`;
CREATE TABLE `black_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `black_list_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_messages
-- ----------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_user_id` bigint(20) NOT NULL COMMENT '发送消息者',
  `to_user_id` bigint(20) NOT NULL COMMENT '接受信息者',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '附件',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '消息类型',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '接受状态',
  `post_at` timestamp(0) NULL DEFAULT NULL COMMENT '发送的时间',
  `read_at` timestamp(0) NULL DEFAULT NULL COMMENT '阅读的时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chat_messages_from_user_id_index`(`from_user_id`) USING BTREE,
  INDEX `chat_messages_to_user_id_index`(`to_user_id`) USING BTREE,
  INDEX `chat_messages_created_at_index`(`created_at`) USING BTREE,
  INDEX `chat_messages_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8404 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for colleges
-- ----------------------------
DROP TABLE IF EXISTS `colleges`;
CREATE TABLE `colleges`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '学校名称',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无' COMMENT '学校类型',
  `properties` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无' COMMENT '学校属性',
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无' COMMENT '所在省份',
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无' COMMENT '所在城市',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2574 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `commenter_id` bigint(20) NOT NULL COMMENT '评论人',
  `obj_id` bigint(20) NOT NULL COMMENT '改评论所属的贴子',
  `college_id` bigint(20) NULL DEFAULT NULL COMMENT '学校',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评论的内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '评论的附件,例图片',
  `ref_comment_id` bigint(20) NULL DEFAULT NULL COMMENT '改评论所评论的评论Id',
  `obj_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '评论的对象的类型,默认是1=表白墙',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '评论的类型',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '评论的状态',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `comments_commenter_id_index`(`commenter_id`) USING BTREE,
  INDEX `comments_obj_id_index`(`obj_id`) USING BTREE,
  INDEX `comments_created_at_index`(`created_at`) USING BTREE,
  INDEX `comments_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8284 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for compare_faces
-- ----------------------------
DROP TABLE IF EXISTS `compare_faces`;
CREATE TABLE `compare_faces`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '对比的照片',
  `confidence` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '比对的相识度',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '比对成功',
  `compare_result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '比对结果',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `compare_faces_user_id_index`(`user_id`) USING BTREE,
  INDEX `compare_faces_created_at_index`(`created_at`) USING BTREE,
  INDEX `compare_faces_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16752 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_part_time_jobs
-- ----------------------------
DROP TABLE IF EXISTS `employee_part_time_jobs`;
CREATE TABLE `employee_part_time_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `part_time_job_id` bigint(20) NOT NULL COMMENT '悬赏ID',
  `user_id` bigint(20) NOT NULL COMMENT '赏金猎人ID',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '于悬赏的状态，1=执行任务中，2=被雇主不信任解除雇佣关系,3=任务完成',
  `score` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务好评，1=好评，2=中评，3=差评',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employee_part_time_jobs_part_time_job_id_index`(`part_time_job_id`) USING BTREE,
  INDEX `employee_part_time_jobs_user_id_index`(`user_id`) USING BTREE,
  INDEX `employee_part_time_jobs_created_at_index`(`created_at`) USING BTREE,
  INDEX `employee_part_time_jobs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 177 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for follows
-- ----------------------------
DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '关注人',
  `obj_id` bigint(20) NOT NULL COMMENT '关注的对象',
  `obj_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '关注对象的类型,1=表白墙,2=卖舍友,3=评论暗恋匹配,4=评论,5=用户',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否取消关注,1=关注中,2=已取消关注',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `follow_nickname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关注人昵称',
  `follow_avatar` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关注人头像',
  `be_follow_nickname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '被关注人昵称',
  `be_follow_avatar` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '被关注人头像',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `follows_user_id_index`(`user_id`) USING BTREE,
  INDEX `follows_obj_id_index`(`obj_id`) USING BTREE,
  INDEX `follows_created_at_index`(`created_at`) USING BTREE,
  INDEX `follows_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7537 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for form_ids
-- ----------------------------
DROP TABLE IF EXISTS `form_ids`;
CREATE TABLE `form_ids`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `form_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信模板消息formid',
  `open_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'openid',
  `expired_at` timestamp(0) NULL DEFAULT NULL COMMENT 'form过期时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `form_ids_user_id_index`(`user_id`) USING BTREE,
  INDEX `form_ids_open_id_index`(`open_id`) USING BTREE,
  INDEX `form_ids_expired_at_index`(`expired_at`) USING BTREE,
  INDEX `form_ids_created_at_index`(`created_at`) USING BTREE,
  INDEX `form_ids_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4917 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for friends
-- ----------------------------
DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户Id',
  `friend_id` bigint(20) NOT NULL COMMENT '好友Id',
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '好友昵称备注',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '好友类型',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `friend_group_id` bigint(20) NULL DEFAULT NULL COMMENT '好友分组Id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `friends_user_id_index`(`user_id`) USING BTREE,
  INDEX `friends_friend_id_index`(`friend_id`) USING BTREE,
  INDEX `friends_created_at_index`(`created_at`) USING BTREE,
  INDEX `friends_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4077 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inboxes
-- ----------------------------
DROP TABLE IF EXISTS `inboxes`;
CREATE TABLE `inboxes`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_id` bigint(20) NOT NULL COMMENT '发送者',
  `to_id` bigint(20) NOT NULL COMMENT '接收者',
  `content` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '信箱的内容',
  `obj_id` bigint(20) NOT NULL,
  `obj_type` tinyint(4) NOT NULL COMMENT '对象的类型',
  `action_type` tinyint(4) NOT NULL COMMENT '信箱的操作类型,例如发帖,评论,回复评论,点赞,关注',
  `post_at` timestamp(0) NULL DEFAULT NULL COMMENT '发送的时间',
  `read_at` timestamp(0) NULL DEFAULT NULL COMMENT '阅读的时间',
  `private` tinyint(1) NOT NULL COMMENT '公开还是匿名新建',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `inboxes_from_id_index`(`from_id`) USING BTREE,
  INDEX `inboxes_to_id_index`(`to_id`) USING BTREE,
  INDEX `inboxes_obj_id_index`(`obj_id`) USING BTREE,
  INDEX `inboxes_created_at_index`(`created_at`) USING BTREE,
  INDEX `inboxes_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27066 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for location
-- ----------------------------
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '经度',
  `longitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '纬度',
  `create_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for match_loves
-- ----------------------------
DROP TABLE IF EXISTS `match_loves`;
CREATE TABLE `match_loves`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) NOT NULL COMMENT '所有者',
  `college_id` bigint(20) NULL DEFAULT NULL COMMENT '学校',
  `user_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '匹配人的名字',
  `match_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '想对他说的话',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '贴子的附件,例如图片',
  `private` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否匿名,默认否',
  `is_password` tinyint(4) NOT NULL COMMENT '是否需要密码,默认需要',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设定的密码',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '类型,是否匿名,默认匿名',
  `status` tinyint(4) NOT NULL,
  `comment_number` int(11) NOT NULL DEFAULT 0 COMMENT '评论数量',
  `praise_number` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `match_loves_owner_id_index`(`owner_id`) USING BTREE,
  INDEX `match_loves_college_id_index`(`college_id`) USING BTREE,
  INDEX `match_loves_user_name_index`(`user_name`) USING BTREE,
  INDEX `被匹配人的名字`(`match_name`) USING BTREE,
  INDEX `match_loves_created_at_index`(`created_at`) USING BTREE,
  INDEX `match_loves_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 255 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for message_sessions
-- ----------------------------
DROP TABLE IF EXISTS `message_sessions`;
CREATE TABLE `message_sessions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `post_phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '发送人的手机号码',
  `receive_phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '接收人人的手机号码',
  `obj_type` tinyint(4) NOT NULL COMMENT '消息对象类型，1=表白墙，2=卖舍友，3=暗恋匹配，4=密语',
  `obj_id` bigint(20) NOT NULL COMMENT '对象ID',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `message_sessions_user_id_index`(`user_id`) USING BTREE,
  INDEX `message_sessions_post_phone_index`(`post_phone`) USING BTREE,
  INDEX `message_sessions_receive_phone_index`(`receive_phone`) USING BTREE,
  INDEX `message_sessions_obj_id_index`(`obj_id`) USING BTREE,
  INDEX `message_sessions_created_at_index`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1243 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 73 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for part_time_jobs
-- ----------------------------
DROP TABLE IF EXISTS `part_time_jobs`;
CREATE TABLE `part_time_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '悬赏人ID',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '悬赏标题',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '悬赏内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '悬赏附件',
  `salary` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '悬赏酬劳',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '悬赏的状态，1=悬赏中，2=任务中，3=悬赏终止，4=悬赏过期，5=悬赏完成',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '预留字段',
  `end_at` timestamp(0) NULL DEFAULT NULL COMMENT '悬赏令的有效期',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `part_time_jobs_user_id_index`(`user_id`) USING BTREE,
  INDEX `part_time_jobs_end_at_index`(`end_at`) USING BTREE,
  INDEX `part_time_jobs_created_at_index`(`created_at`) USING BTREE,
  INDEX `part_time_jobs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 408 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `poster_id` int(11) NOT NULL COMMENT '贴子的发表人',
  `college_id` int(11) NULL DEFAULT NULL COMMENT '所属学校',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '贴子的内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '贴子的附件,例如图片',
  `topic` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无' COMMENT '主题,预留字段',
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '预留字段',
  `private` tinyint(1) NOT NULL COMMENT '公开还是匿名',
  `comment_number` int(11) NOT NULL DEFAULT 0 COMMENT '评论数量',
  `praise_number` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `mobile` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `new_column` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `posts_poster_id_index`(`poster_id`) USING BTREE,
  INDEX `posts_college_id_index`(`college_id`) USING BTREE,
  INDEX `posts_created_at_index`(`created_at`) USING BTREE,
  INDEX `posts_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17241 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for praises
-- ----------------------------
DROP TABLE IF EXISTS `praises`;
CREATE TABLE `praises`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) NOT NULL COMMENT '点赞人',
  `obj_id` bigint(20) NOT NULL COMMENT '被点赞对象Id',
  `obj_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '被点赞对象的类型',
  `college_id` bigint(20) NULL DEFAULT NULL COMMENT '学校Id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `praises_owner_id_index`(`owner_id`) USING BTREE,
  INDEX `praises_obj_id_index`(`obj_id`) USING BTREE,
  INDEX `praises_created_at_index`(`created_at`) USING BTREE,
  INDEX `praises_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11463 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for qiniu_tokens
-- ----------------------------
DROP TABLE IF EXISTS `qiniu_tokens`;
CREATE TABLE `qiniu_tokens`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '七牛上传的凭证',
  `expired_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '过期时间时间',
  `created_at` timestamp(0) NULL DEFAULT NULL COMMENT '该记录创建的时间',
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `qiniu_tokens_expired_at_index`(`expired_at`) USING BTREE,
  INDEX `qiniu_tokens_created_at_index`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for run_steps
-- ----------------------------
DROP TABLE IF EXISTS `run_steps`;
CREATE TABLE `run_steps`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `status` tinyint(5) NOT NULL DEFAULT 1 COMMENT '是否已使用，1=未使用，2=已使用',
  `type` tinyint(5) NOT NULL DEFAULT 1 COMMENT '是否是当天的数据',
  `step` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户的步数',
  `run_at` timestamp(0) NULL DEFAULT NULL COMMENT '步数的日期',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `run_steps_user_id_index`(`user_id`) USING BTREE,
  INDEX `run_steps_run_at_index`(`run_at`) USING BTREE,
  INDEX `run_steps_created_at_index`(`created_at`) USING BTREE,
  INDEX `run_steps_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 188189 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sale_friends
-- ----------------------------
DROP TABLE IF EXISTS `sale_friends`;
CREATE TABLE `sale_friends`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) NOT NULL COMMENT '所属人Id',
  `college_id` bigint(20) NULL DEFAULT NULL COMMENT '学校Id',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '舍友的名字',
  `gender` tinyint(4) NOT NULL DEFAULT 1 COMMENT '性别,默认是男',
  `major` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '专业',
  `expectation` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简单介绍下喜欢什么样的人,期望',
  `introduce` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '介绍一下舍友',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '贴子的附件,例如图片',
  `comment_number` int(11) NOT NULL DEFAULT 0 COMMENT '评论数量',
  `praise_number` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数量',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '预留字段',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '预留字段',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sale_friends_owner_id_index`(`owner_id`) USING BTREE,
  INDEX `sale_friends_college_id_index`(`college_id`) USING BTREE,
  INDEX `sale_friends_name_index`(`name`) USING BTREE,
  INDEX `sale_friends_created_at_index`(`created_at`) USING BTREE,
  INDEX `sale_friends_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1407 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for secret_messages
-- ----------------------------
DROP TABLE IF EXISTS `secret_messages`;
CREATE TABLE `secret_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_user_id` bigint(20) NOT NULL COMMENT '发送人用户ID',
  `receive_user_id` bigint(20) NULL DEFAULT NULL COMMENT '接收人id',
  `message_session_id` bigint(20) NOT NULL COMMENT '短信会话ID',
  `number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0000' COMMENT '编号',
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '附件的内容',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否已读，1=未读，2=已读',
  `delay_at` timestamp(0) NULL DEFAULT NULL COMMENT '延期发送的时间',
  `send_at` timestamp(0) NULL DEFAULT NULL COMMENT '短信发送的日期',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `secret_messages_post_user_id_index`(`post_user_id`) USING BTREE,
  INDEX `secret_messages_receive_user_id_index`(`receive_user_id`) USING BTREE,
  INDEX `secret_messages_message_session_id_index`(`message_session_id`) USING BTREE,
  INDEX `secret_messages_number_index`(`number`) USING BTREE,
  INDEX `secret_messages_code_index`(`code`) USING BTREE,
  INDEX `secret_messages_delay_at_index`(`delay_at`) USING BTREE,
  INDEX `secret_messages_created_at_index`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1256 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for secret_messages_visit_logs
-- ----------------------------
DROP TABLE IF EXISTS `secret_messages_visit_logs`;
CREATE TABLE `secret_messages_visit_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户微信昵称',
  `user_id` bigint(20) NOT NULL COMMENT '用户Id',
  `secret_message_id` bigint(20) NOT NULL COMMENT '秘言ID',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `secret_messages_visit_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `secret_messages_visit_logs_secret_message_id_index`(`secret_message_id`) USING BTREE,
  INDEX `secret_messages_visit_logs_created_at_index`(`created_at`) USING BTREE,
  INDEX `secret_messages_visit_logs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for send_messages
-- ----------------------------
DROP TABLE IF EXISTS `send_messages`;
CREATE TABLE `send_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号码',
  `message_session_id` bigint(20) NULL DEFAULT NULL COMMENT '消息的ID',
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=短息验证码，2=...',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '发送状态，1=成功，2=失败',
  `expired_at` timestamp(0) NULL DEFAULT NULL COMMENT '过期时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `send_messages_mobile_index`(`mobile`) USING BTREE,
  INDEX `send_messages_message_session_id_index`(`message_session_id`) USING BTREE,
  INDEX `send_messages_code_index`(`code`) USING BTREE,
  INDEX `send_messages_expired_at_index`(`expired_at`) USING BTREE,
  INDEX `send_messages_created_at_index`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 785 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for template_key_words
-- ----------------------------
DROP TABLE IF EXISTS `template_key_words`;
CREATE TABLE `template_key_words`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板消息ID',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `content` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '内容',
  `keyword_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息模板关键字组合模板排列ID',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `template_key_words_created_at_index`(`created_at`) USING BTREE,
  INDEX `template_key_words_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for template_logs
-- ----------------------------
DROP TABLE IF EXISTS `template_logs`;
CREATE TABLE `template_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) NOT NULL COMMENT '所属小程序',
  `open_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '发送人',
  `template_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发送的内容',
  `result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '返回结果',
  `page` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转的页面',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '发送状态，1=成功，2=失败',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '预留字段',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `template_logs_created_at_index`(`created_at`) USING BTREE,
  INDEX `template_logs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 542 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for templates
-- ----------------------------
DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) NOT NULL COMMENT '所属小程序',
  `template_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板ID',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息模板标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '模板消息内容',
  `keyword_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息模板关键字组合模板排列ID',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `templates_app_id_index`(`app_id`) USING BTREE,
  INDEX `templates_created_at_index`(`created_at`) USING BTREE,
  INDEX `templates_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 977 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for topics
-- ----------------------------
DROP TABLE IF EXISTS `topics`;
CREATE TABLE `topics`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '话题发布者，可以是后台管理员和用户',
  `app_id` int(11) NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '发帖人类型，1=后台管理员，2=用户',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '标题',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '内容',
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '附件',
  `praise_number` bigint(20) NOT NULL DEFAULT 0 COMMENT '点赞人数',
  `view_number` bigint(20) NOT NULL DEFAULT 0 COMMENT '浏览人数',
  `comment_number` bigint(20) NOT NULL DEFAULT 1 COMMENT '评论人数',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态，1=下架，2=上架',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `topics_user_id_index`(`user_id`) USING BTREE,
  INDEX `topics_created_at_index`(`created_at`) USING BTREE,
  INDEX `topics_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 596 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for travel_log_pois
-- ----------------------------
DROP TABLE IF EXISTS `travel_log_pois`;
CREATE TABLE `travel_log_pois`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `travel_log_id` bigint(20) NOT NULL COMMENT '所属旅行日志',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '周边的名字，例如酒店名字，景点名字',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '周边的地址',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'poi的类型，1=酒店，2=美食，3=景点',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `travel_log_pois_travel_log_id_index`(`travel_log_id`) USING BTREE,
  INDEX `travel_log_pois_created_at_index`(`created_at`) USING BTREE,
  INDEX `travel_log_pois_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16630 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for travel_logs
-- ----------------------------
DROP TABLE IF EXISTS `travel_logs`;
CREATE TABLE `travel_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `travel_plan_id` bigint(20) NOT NULL COMMENT '旅行计划id',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抵达点的名字',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抵达点的地址',
  `province` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '省',
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '城市',
  `district` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '县',
  `point_id` bigint(20) NULL DEFAULT NULL COMMENT '所属站点',
  `length` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '地图坐标的距离',
  `total_length` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '总的地图坐标的距离',
  `distance` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '行程',
  `step` double(8, 2) NOT NULL DEFAULT 0.00 COMMENT '步数',
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抵达点地理维度',
  `longitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抵达点地理经度',
  `run_at` timestamp(0) NULL DEFAULT NULL COMMENT '日期',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `travel_logs_travel_plan_id_index`(`travel_plan_id`) USING BTREE,
  INDEX `travel_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `travel_logs_run_at_index`(`run_at`) USING BTREE,
  INDEX `travel_logs_created_at_index`(`created_at`) USING BTREE,
  INDEX `travel_logs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10141 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for travel_plan_points
-- ----------------------------
DROP TABLE IF EXISTS `travel_plan_points`;
CREATE TABLE `travel_plan_points`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `travel_plan_id` bigint(20) NOT NULL COMMENT '旅行计划',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '站点的名字',
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '站点的地址',
  `latitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '站点地理维度',
  `longitude` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '站点地理经度',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '站点的顺序',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '站点的类型，1=起点，2=途径站点，3=终点',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否经过站点，1=未抵达，2=已抵达，3=用户已走出站点范围',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `travel_plan_points_travel_plan_id_index`(`travel_plan_id`) USING BTREE,
  INDEX `travel_plan_points_created_at_index`(`created_at`) USING BTREE,
  INDEX `travel_plan_points_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2074 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for travel_plans
-- ----------------------------
DROP TABLE IF EXISTS `travel_plans`;
CREATE TABLE `travel_plans`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '旅行的目标',
  `distance` bigint(20) NOT NULL COMMENT '旅行的总路程，单位是米',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '旅行计划的状态，1=旅行中，2=已终止，3等于已完成',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `travel_plans_user_id_index`(`user_id`) USING BTREE,
  INDEX `travel_plans_created_at_index`(`created_at`) USING BTREE,
  INDEX `travel_plans_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 834 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信初始昵称',
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信初始头像',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户证实姓名',
  `student_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '学号',
  `grade` tinyint(4) NOT NULL COMMENT '用户年级,1=大一，2=大二，3=大三，4=大四，5=其他',
  `major` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '专业',
  `college` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '所属学院',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_profiles_user_id_index`(`user_id`) USING BTREE,
  INDEX `user_profiles_created_at_index`(`created_at`) USING BTREE,
  INDEX `user_profiles_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 446 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_visit_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_visit_logs`;
CREATE TABLE `user_visit_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户微信昵称',
  `user_id` bigint(20) NOT NULL COMMENT '用户Id',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_visit_logs_user_id_index`(`user_id`) USING BTREE,
  INDEX `user_visit_logs_created_at_index`(`created_at`) USING BTREE,
  INDEX `user_visit_logs_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56825 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) NOT NULL COMMENT '小程序id',
  `nickname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户微信昵称',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预留账号密码',
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '预留手机号码字段',
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '微信头像',
  `gender` tinyint(4) NOT NULL DEFAULT 0 COMMENT '默认一个性别',
  `open_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `union_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无',
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无',
  `language` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zh_CN',
  `province` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '无',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户类型',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户状态',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `active_value` int(11) NOT NULL DEFAULT 0 COMMENT '活跃度',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `personal_signature` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '你在路上随便碰到的一个路人，都是别人做梦都想见到的那人' COMMENT '个性签名',
  `follow_num` int(11) NOT NULL DEFAULT 0 COMMENT '关注数',
  `fans_num` int(11) NOT NULL DEFAULT 0 COMMENT '粉丝数',
  `post_num` int(11) NOT NULL DEFAULT 0 COMMENT '帖子动态数',
  `clock_num` int(11) NOT NULL DEFAULT 0 COMMENT '打卡天数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `users_app_id_index`(`app_id`) USING BTREE,
  INDEX `users_mobile_index`(`mobile`) USING BTREE,
  INDEX `users_open_id_index`(`open_id`) USING BTREE,
  INDEX `users_union_id_index`(`union_id`) USING BTREE,
  INDEX `users_created_at_index`(`created_at`) USING BTREE,
  INDEX `users_updated_at_index`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43025 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `v_id` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '视频id',
  `app_id` bigint(20) NOT NULL,
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '视频链接地址',
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '视频标题',
  `sort` int(10) NOT NULL DEFAULT 1 COMMENT '序号',
  `introduction` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '视频简介',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `videos_app_id_index`(`app_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
