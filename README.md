# WooCommerce Pricing Panels

WooCommerce Pricing Panels is a WordPress plugin designed to generate and display HTML pricing panels for WooCommerce products, based on a selected product category. This plugin allows users to easily showcase product pricing and details on any page by selecting a specific category from which the products are displayed.

## Features

- **Category Selection**: Users can select specific WooCommerce product categories directly from the page editor.
- **Dynamic Pricing Panels**: Automatically generates pricing panels that display product information such as the title, price, and a customizable 'Subscribe Now' button.
- **Customizable Layout**: The plugin includes basic CSS for the layout of the pricing panels, which can be customized further by the theme or additional custom styles.

## Installation

1. Download the plugin files and upload them to your WordPress site's `wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

### Adding the Category Selector to a Page

When editing a page, a new meta box titled "Select Product Category" will appear. Use this box to select the category from which the products should be displayed.

### Displaying Pricing Panels

To display the pricing panels on a page or post, insert the following shortcode where you want the panels to appear:


[pricing_panels]

The plugin will automatically generate the pricing panels for products in the selected category.

This plugin is intended for use with WooCommerce and assumes that WooCommerce is installed and activated on your WordPress site.


## Shortcode Attributes
Currently, the shortcode does not support any attributes, and it only displays products based on the category selected in the page meta box.

##Styling
The plugin provides basic styles which can be customized. You can override the default CSS in your theme or in additional CSS sections of the WordPress customizer.

## Developer Information
Author: D Kandekore
Version: 1.0

## License
This plugin is open-source and can be freely used and modified. No explicit license is applied.
