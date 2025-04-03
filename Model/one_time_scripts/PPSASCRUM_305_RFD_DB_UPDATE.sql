-- TICKET: PPSASCRUM-305


-- showing RFD option to create calculator line item
INSERT INTO `calculators` (`name`, `slug`, `status`)
VALUES
	('Ripplefold Drapery', 'ripplefold-drapery', 'Active');
	

-- new inbound freight calculation multipliers and labor cost
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `setting_label`, `fieldtype`)
VALUES
	('inbound_freight_multiplier_totyds50', '50', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds < = 50', 'number'),
	('inbound_freight_multiplier_totyds51-100', '100', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds51-100', 'number'),
	('inbound_freight_multiplier_totyds101-200', '150', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds101-200', 'number'),
	('inbound_freight_multiplier_totyds201-300', '250', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds201-300', 'number'),
	('inbound_freight_multiplier_totyds301-500', '400', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds301-500', 'number'),
	('inbound_freight_multiplier_totyds501-600', '500', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds501-600', 'number'),
	('inbound_freight_multiplier_totyds600plus', '500', 'Inbound Freight Calculations', 'Inbound Freight $Amount  to be divided /Totyds when Totyds600+', 'number'),
	('wt_ripplefold_lined_labor_mom_per_width', '31.15', '', 'Wt Ripplefold Lined Labor Mom Per Width', 'number'),
	('wt_ripplefold_lined_labor_com_per_width', '32.35', '', 'Wt Ripplefold Lined Labor Com Per Width', 'number'),
	('wt_ripplefold_unlined_labor_mom_per_width', '29.40', '', 'Wt Ripplefold Unlined Labor Mom Per Width', 'number'),
	('wt_ripplefold_unlined_labor_com_per_width', '30.55', '', 'Wt Ripplefold Unlined Labor Com Per Width', 'number'),
	('wt_ripplefold_interlined_labor_mom_per_width', '40.00', '', 'Wt Ripplefold Interlined Labor Mom Per Width', 'number'),
	('wt_ripplefold_interlined_labor_com_per_width', '41.60', '', 'Wt Ripplefold Interlined Labor Com Per Width', 'number');


-- RFD calculator library image
INSERT INTO `library_images` (`image_title`, `categories`, `calculator_specific`, `filename`, `time`, `tags`, `added_by`, `status`)
VALUES
	('RFD generic image', 'Misc', NULL, 'RFD generic image.png', 1738585716, '', 1, 'Active');
