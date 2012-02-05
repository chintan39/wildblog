-- This script converts links to images/files in 'text' columns of all tables 
-- from project/images/ to new location media/
-- 
-- we've got tables with text field:
-- SELECT table_name
-- FROM information_schema.`COLUMNS`
-- WHERE `COLUMN_NAME` LIKE 'text' and `TABLE_SCHEMA`='jitkapokorna'

update `wildblog_base_config` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_base_dictionary` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_base_email_log` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_base_languages` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_base_messages` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_advertisements_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_articles_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_contact_form` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_html_areas_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_menu_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_menu_items_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_news_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_basic_tags_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_blog_comments_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_blog_posts_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_blog_tags_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_calendar_events_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_calendar_tags_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_categories_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_manofacturers_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_products_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_properties_groups_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_properties_options_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_references_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_units_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_commodity_vat_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_eshop_products_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_faq_questions_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_gallery_galleries_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_linkbuilding_tags_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_newsletter_messages_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_references_references_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_research_options_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_research_questions_ext` set `text`=replace(`text`, 'project/images/', 'media/');
update `wildblog_research_researches_ext` set `text`=replace(`text`, 'project/images/', 'media/');

update `wildblog_base_config` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_base_dictionary` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_base_email_log` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_base_languages` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_base_messages` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_advertisements_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_articles_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_contact_form` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_html_areas_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_menu_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_menu_items_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_news_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_basic_tags_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_blog_comments_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_blog_posts_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_blog_tags_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_calendar_events_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_calendar_tags_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_categories_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_manofacturers_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_products_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_properties_groups_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_properties_options_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_references_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_units_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_commodity_vat_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_eshop_products_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_faq_questions_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_gallery_galleries_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_linkbuilding_tags_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_newsletter_messages_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_references_references_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_research_options_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_research_questions_ext` set `text`=replace(`text`, 'project/files/', 'media/');
update `wildblog_research_researches_ext` set `text`=replace(`text`, 'project/files/', 'media/');


