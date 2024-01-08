#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content
(
	image_classes                           VARCHAR(255)     DEFAULT ''            NOT NULL,
	element_classes                         VARCHAR(255)     DEFAULT ''            NOT NULL,
	effects                                 VARCHAR(255)     DEFAULT ''            NOT NULL,
	imagecols_xs                            TINYINT(4)       DEFAULT '1'           NOT NULL,
	imagecols_sm                            TINYINT(4)       DEFAULT '1'           NOT NULL,
	imagecols_md                            TINYINT(4)       DEFAULT '1'           NOT NULL,
	imagecols_lg                            TINYINT(4)       DEFAULT '1'           NOT NULL,
	imagecols_xl                            TINYINT(4)       DEFAULT '1'           NOT NULL,
	imagecols_xxl                           TINYINT(4)       DEFAULT '1'           NOT NULL,
	background_media                        INT(11) UNSIGNED DEFAULT '0',
	html_tag                                VARCHAR(10)      DEFAULT 'div'         NOT NULL,
	frame_class                             VARCHAR(255)     DEFAULT 'default'     NOT NULL,
	bg_color_class                          VARCHAR(255)     DEFAULT ''            NOT NULL,
	padding_top_class                       VARCHAR(60)      DEFAULT ''            NOT NULL,
	padding_bottom_class                    VARCHAR(60)      DEFAULT ''            NOT NULL,
	filelink_sorting                        VARCHAR(20)      DEFAULT ''            NOT NULL,
	filelink_sorting_direction              VARCHAR(10)      DEFAULT 'ascending'   NOT NULL,
	footer                                  VARCHAR(255)     DEFAULT ''            NOT NULL,
	footer_position                         VARCHAR(255)     DEFAULT ''            NOT NULL,
	footer_link                             VARCHAR(1024)    DEFAULT ''            NOT NULL,
	footer_layout                           VARCHAR(30)      DEFAULT '0'           NOT NULL,
	tx_wst3bootstrap_card_elements          int(11) unsigned DEFAULT '0',
	tx_wst3bootstrap_card_elements_backside int(11) unsigned DEFAULT '0',
	image_overlay                           TINYINT(4)       DEFAULT '0'           NOT NULL,
	tx_wst3bootstrap_counterbar_item        int(11) unsigned DEFAULT '0',
	icon                                    VARCHAR(1024)    DEFAULT '',
	icon_color                              VARCHAR(40)      DEFAULT ''            NOT NULL,
	icon_style                              VARCHAR(40)      DEFAULT ''            NOT NULL,
	icon_size                               VARCHAR(2)       DEFAULT ''            NOT NULL,
	form_layout                             VARCHAR(60)      DEFAULT ''            NOT NULL,
	grid_layout                             VARCHAR(40)      DEFAULT ''            NOT NULL,
	grid_layout_responsive_bg               VARCHAR(40)      DEFAULT ''            NOT NULL,
	icon_position                           int(11) unsigned DEFAULT '0',
	full_width                              TINYINT(4)       DEFAULT '0'           NOT NULL,
	fixed_image                             TINYINT(4)       DEFAULT '0'           NOT NULL,
	levels                                  TINYINT(4)       DEFAULT '7'           NOT NULL,
	tx_wst3bootstrap_migrated_version       VARCHAR(6)       DEFAULT ''            NOT NULL,
	aos_effect                              VARCHAR(1024)    DEFAULT 'none'        NOT NULL,
	aos_easing                              VARCHAR(1024)    DEFAULT 'ease-in-out' NOT NULL,
	aos_once                                TINYINT(4)       DEFAULT '0'           NOT NULL,
	aos_offset                              int(11) unsigned DEFAULT '120'         NOT NULL,
	aos_delay                               int(11) unsigned DEFAULT '0'           NOT NULL,
	aos_duration                            int(11) unsigned DEFAULT '500'         NOT NULL,
	visibility_flags                        int(11)          DEFAULT '63'          NOT NULL,
	image_loading_behaviour                 VARCHAR(10)      DEFAULT 'auto'        NOT NULL,
	use_link_overlay                        TINYINT(4)       DEFAULT '0'           NOT NULL
);


CREATE TABLE pages
(
	heroimage            INT(11)     DEFAULT NULL,
	heroimage_big        INT(11)     DEFAULT NULL,
	heromedia_fullscreen INT(11)     DEFAULT NULL,
	preview_image        INT(11)     DEFAULT NULL,
	header_darken        TINYINT(4)  DEFAULT '0'     NOT NULL,
	header_size          VARCHAR(20) DEFAULT 'small' NOT NULL,
	icon                 VARCHAR(20) DEFAULT ''      NOT NULL,
	jumbotron            TINYINT(4)  DEFAULT '1'     NOT NULL,
	heroContainer        TINYINT(4)  DEFAULT '1'     NOT NULL,
	heroslide            TINYINT(4)  DEFAULT '-1'    NOT NULL,
	prevent_link         TINYINT(4)  DEFAULT '0'     NOT NULL,
	nav_hide_subtree     TINYINT(4)  DEFAULT '0'     NOT NULL
);


CREATE TABLE sys_file_reference
(
	text_visible    TEXT,
	text_hover      TEXT,
	extended_design VARCHAR(40) DEFAULT '' NOT NULL
);


CREATE TABLE tx_wst3bootstrap_card_element
(
	uid                       int(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
	pid                       int(11)              NOT NULL DEFAULT 0,
	tt_content                int(11)              NOT NULL DEFAULT 0,
	t3ver_oid                 int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3ver_id                  int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3ver_wsid                int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3ver_label               varchar(255)         NOT NULL DEFAULT '',
	t3ver_state               smallint(6)          NOT NULL DEFAULT 0,
	t3ver_stage               int(11)              NOT NULL DEFAULT 0,
	t3ver_count               int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3ver_tstamp              int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3ver_move_id             int(10) UNSIGNED     NOT NULL DEFAULT 0,
	t3_origuid                int(10) UNSIGNED     NOT NULL DEFAULT 0,
	tstamp                    int(11) UNSIGNED     NOT NULL DEFAULT 0,
	crdate                    int(11) UNSIGNED     NOT NULL DEFAULT 0,
	cruser_id                 int(11) UNSIGNED     NOT NULL DEFAULT 0,
	hidden                    smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	sorting                   int(11)              NOT NULL DEFAULT 0,
	CType                     varchar(255)         NOT NULL DEFAULT '',
	header                    varchar(255)         NOT NULL DEFAULT '',
	bodytext                  mediumtext                    DEFAULT NULL,
	layout                    int(11) UNSIGNED     NOT NULL DEFAULT 0,
	deleted                   smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	starttime                 int(11) UNSIGNED     NOT NULL DEFAULT 0,
	endtime                   int(11) UNSIGNED     NOT NULL DEFAULT 0,
	subheader                 varchar(255)         NOT NULL DEFAULT '',
	fe_group                  varchar(255)         NOT NULL DEFAULT '0',
	header_link               varchar(1024)        NOT NULL DEFAULT '',
	image_zoom                smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	header_layout             varchar(30)          NOT NULL DEFAULT '0',
	list_type                 varchar(255)         NOT NULL DEFAULT '',
	file_collections          text                          DEFAULT NULL,
	filelink_size             smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	filelink_sorting          varchar(20)          NOT NULL DEFAULT '',
	target                    varchar(30)          NOT NULL DEFAULT '',
	date                      int(10) UNSIGNED     NOT NULL DEFAULT 0,
	sys_language_uid          int(11)              NOT NULL DEFAULT 0,
	pi_flexform               mediumtext                    DEFAULT NULL,
	accessibility_title       varchar(30)          NOT NULL DEFAULT '',
	accessibility_bypass      smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	accessibility_bypass_text varchar(30)          NOT NULL DEFAULT '',
	l18n_parent               int(10) UNSIGNED     NOT NULL DEFAULT 0,
	l18n_diffsource           mediumblob                    DEFAULT NULL,
	editlock                  smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	rowDescription            text                          DEFAULT NULL,
	assets                    int(11) UNSIGNED     NOT NULL DEFAULT 0,
	header_position           varchar(255)         NOT NULL DEFAULT '',
	l10n_source               int(10) UNSIGNED     NOT NULL DEFAULT 0,
	l10n_state                text                          DEFAULT NULL,
	space_before_class        varchar(60)                   DEFAULT NULL,
	space_after_class         varchar(60)                   DEFAULT NULL,
	field                     varchar(255)                  DEFAULT NULL,
	uploads_description       tinyint(1) unsigned           DEFAULT '0' NOT NULL,
	uploads_type              tinyint(3) unsigned           DEFAULT '0' NOT NULL,

	PRIMARY KEY (`uid`),
	KEY parent (pid, sorting),
	KEY t3ver_oid (t3ver_oid, t3ver_wsid),
	KEY language (l18n_parent, sys_language_uid)
);



CREATE TABLE tx_wst3bootstrap_counterbar_item
(
	uid              int(11) unsigned              NOT NULL auto_increment,
	pid              int(11)           DEFAULT '0' NOT NULL,

	tt_content       int(11) unsigned  DEFAULT '0',
	header           varchar(255)      DEFAULT ''  NOT NULL,
	fontawesome_icon varchar(255)      DEFAULT ''  NOT NULL,
	icon             varchar(255)      DEFAULT ''  NOT NULL,
	count_number     varchar(255)      DEFAULT ''  NOT NULL,
	counter_text     text,
	sorting          int(11)           DEFAULT '0' NOT NULL,
	tstamp           int(11) unsigned  DEFAULT '0' NOT NULL,
	crdate           int(11) unsigned  DEFAULT '0' NOT NULL,
	cruser_id        int(11) unsigned  DEFAULT '0' NOT NULL,
	deleted          smallint unsigned DEFAULT '0' NOT NULL,
	hidden           smallint unsigned DEFAULT '0' NOT NULL,
	starttime        int(11) unsigned  DEFAULT '0' NOT NULL,
	endtime          int(11) unsigned  DEFAULT '0' NOT NULL,

	sys_language_uid int(11)           DEFAULT '0' NOT NULL,
	l10n_parent      int(11) unsigned  DEFAULT '0' NOT NULL,
	l10n_diffsource  mediumblob                    NULL,

	t3ver_oid        int(11) unsigned  DEFAULT '0' NOT NULL,
	t3ver_id         int(11) unsigned  DEFAULT '0' NOT NULL,
	t3ver_wsid       int(11) unsigned  DEFAULT '0' NOT NULL,
	t3ver_label      varchar(255)      DEFAULT ''  NOT NULL,
	t3ver_state      smallint          DEFAULT '0' NOT NULL,
	t3ver_stage      int(11)           DEFAULT '0' NOT NULL,
	t3ver_count      int(11) unsigned  DEFAULT '0' NOT NULL,
	t3ver_tstamp     int(11) unsigned  DEFAULT '0' NOT NULL,
	t3ver_move_id    int(11) unsigned  DEFAULT '0' NOT NULL,
	t3_origuid       int(11) unsigned  DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid, t3ver_wsid),
	KEY language (l10n_parent, sys_language_uid)
);
