<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-08-06 01:00:13 --> Could not find the language line "username"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "password"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "first_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "last_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "username"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "email"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "phone"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "company"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "gender"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "password"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "confirm_password"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "first_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "last_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "username"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "email"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "phone"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "company"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "gender"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "product_code"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "product_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "cname"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "barcode_symbology"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "product_unit"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "product_price"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "contact_person"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "company"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "address"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "city"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "phone"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "first_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "last_name"
ERROR - 2023-08-06 01:00:13 --> Could not find the language line "email"
ERROR - 2023-08-06 08:57:33 --> Could not find the language line "Email"
ERROR - 2023-08-06 08:57:57 --> Could not find the language line "Billings"
ERROR - 2023-08-06 08:57:57 --> Could not find the language line "Billings"
ERROR - 2023-08-06 08:58:40 --> Could not find the language line "Billings"
ERROR - 2023-08-06 08:58:40 --> Could not find the language line "Billings"
ERROR - 2023-08-06 09:00:13 --> Could not find the language line "Create Billing"
ERROR - 2023-08-06 09:00:13 --> Could not find the language line "Create Billing"
ERROR - 2023-08-06 09:02:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ON client.company_code = billing.company_code 
                        LEFT JOIN' at line 3 - Invalid query: SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, client_qb_id.qb_customer_id, our_service_qb_info.qb_item_id, our_service_info.service_name, currency.currency as currency_name, gst_category.category as gst_category_name FROM billing 
                        LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                        LEFT JOIN    ON client.company_code = billing.company_code 
                        LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                        LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '9130349657033056'
                        LEFT JOIN currency ON currency.id = billing.currency_id 
                        LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                        LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '9130349657033056'
                        WHERE billing.id = '14139' ORDER BY billing_service.id
ERROR - 2023-08-06 09:03:40 --> Could not find the language line "Our Firm"
ERROR - 2023-08-06 09:03:40 --> Could not find the language line "Our Firm"
ERROR - 2023-08-06 09:03:44 --> Could not find the language line "Billings"
ERROR - 2023-08-06 09:03:44 --> Could not find the language line "Billings"
ERROR - 2023-08-06 09:07:24 --> Could not find the language line "Billings"
ERROR - 2023-08-06 09:07:24 --> Could not find the language line "Billings"
ERROR - 2023-08-06 17:23:42 --> Could not find the language line "Email"
