# Argento Luxury

## Installation

### For clients

There are several ways to install extension for clients:

 1. If you've bought the product at Magento's Marketplace - use
    [Marketplace installation instructions](https://docs.magento.com/marketplace/user_guide/buyers/install-extension.html)
 2. Otherwise, you have two options:
    - Install the sources directly from [our repository](https://docs.swissuplabs.com/m2/argento/installation/composer/) - **recommended**
    - Download archive and use [manual installation](https://docs.swissuplabs.com/m2/argento/installation/manual/)

### For developers

Use this approach if you have access to our private repositories!

```bash
composer config repositories.swissup composer https://docs.swissuplabs.com/packages/
composer require swissup/theme-frontend-argento-luxury:dev-master --prefer-source
bin/magento module:enable\
    Swissup_Core\
    Swissup_Rtl
bin/magento setup:upgrade
```
