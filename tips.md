# 1. **PHP CODE** #
## + General ##
* Get Object Manager Instance
```php
<?php 
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
```
* GET CURRENT URL
```php 
$this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
$link = $block->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => ['str1' => 'value1', 'str2' => 'value2']]);
$this->getUrl('productquickview/id/', ['_current'=>false,'_use_rewrite'=>false]);
```
* Tao block tu controler, model, ...
    * Other:
    ```php
        protected $_blockFactory;
        public function __construct(
           \Magento\Framework\View\Element\BlockFactory $blockFactory
        ){
            $this->_blockFactory = $blockFactory;
        }
    ```
    * phtml:
    ```phtml
    echo $this->getLayout()
              ->createBlock('Magento\Cms\Block\Block')
              ->setBlockId('your_block_identifier')
              ->setTemplate('BingDigital_Swatches::product
              /view/type/renderer.phtml')
              ->toHtml();
    ```

    * controller:
	
    ```php
    $this->_view->getLayout()->createBlock('Full\Block\Class\Name\Here');
    ```
	- model:
    ```php
     $this->_blockFactory->createBlock('Full\Block\Class\Name\Here');
    ```
	* set data
	```php
	$block = $this->frameworkViewLayout
			    ->createBlock(
			        "Company\Module\Block\Hello",
			        "block_name",
			        [
			            'data' => [
			                'my_arg' => 'testvalue'
			            ]
			        ]
			    )
			    ->setData('area', 'frontend')
			    ->setTemplate($template)
			    ->toHtml();
    ```




*  hiển thị attribute của sản phẩm theo group name
```php
<?php
$obj = \Magento\Framework\App\ObjectManager::getInstance();
$attributeSetId = $currentProduct->getAttributeSetId();
$config= $obj->get('Magento\Catalog\Model\Config');
$attributeGroupId   = $config->getAttributeGroupId($attributeSetId, 'Warranty Information');
$productAttributes  = $currentProduct->getAttributes();
?>
<?php foreach ($productAttributes as $attribute) { ?>
   <?php  if ($attribute->isInGroup($attributeSetId, $attributeGroupId) && $attribute->getFrontend()->getValue($currentProduct)) { ?>
       <div class="warranty-name">
           <span><?php echo __($attribute->getFrontendLabel()); ?></span>
           <div class="warranty-value">
               <?php echo $attribute->getFrontend()->getValue($currentProduct); ?>
           </div>
       </div>
   <?php } ?>
<?php } ?>
```
* Gọi Label của attribute từ product:
```php
$_product->getResource()->getAttribute('club_type')->getFrontend()->getValue($_product)
```

## + Helper ##
## + Model ##
## + Controller ##
## + View ##
### - Layout ###
### - Templates ###
## + Console ##
### - Deploy ###
- Modman Deploy
```modman 
    modman deploy-all --force
```
- Magento Deploy
```bash 
php bin/magento setup:upgrade && php bin/magento setup:di:compile && php bin/magento setup:static-content:deploy -f -t Magento/backend zh_Hant_TW en_US
```
- Style Deploy
```
php bin/magento dev:source-theme:deploy --type="less" --locale="zh_Hant_TW" --area="frontend" --theme="Niuniu/default" css/styles-l css/styles-m && grunt less:niuniu_tw
```
- Cache Flush
```
php bin/magento c:f && chmod -R 777 var/ pub/ generated/
```
- Create Account
```
php bin/magento admin:user:create --admin-user='admin' --admin-password='admin123' --admin-email='thanhnv@ecommage.com' --admin-firstname='Admin' --admin-lastname='Ecommage'
```
- Create File i18n
```
sudo php bin/magento i18n:collect-phrases -o "/var/www/html/ecommage/niuniu/xx_YY.csv" /var/www/html/ecommage/niuniu/.modman/
```

composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition <installation directory name>
```
    
```    
sudo php bin/magento setup:install --base-url=http://m2.bamboo.com/ --db-host=localhost --db-name=ecommage_m2_bamboo --db-user=admin --db-password=1 --admin-firstname=Thanh --admin-lastname=NV --admin-email=thanhnv@ecommage.com --admin-user=ecommage --admin-password=ecommage123 --language=en_US --currency=USD --timezone=America/Chicago --use-rewrites=1
```
- Install sample Data
```magento2
php <your Magento install dir>/bin/magento sampledata:deploy
```

- Tools
    *  **pestle**
        * `curl -LO http://pestle.pulsestorm.net/pestle.phar`
        * `./pestle.phar magento2:generate:crud-model`
        * `./pestle.phar magento2:generate:menu`
        * `./pestle.phar magento2:generate:acl`
        * `./pestle.phar magento2:generate:route`
        * `./pestle.phar magento2:generate:view`
        * `./pestle.phar magento2:generate:ui:grid`
        * `./pestle.phar magento2:generate:ui:form`
        
    * **n98**
        * `wget https://files.magerun.net/n98-magerun2.phar`
        * `php n98-magerun2.phar sys:cron:run`

    * **Check code**
        * PHP MD
```
phpmd .modman xml cleancode --reportfile target/pmd.xml
```
        * PHP CPD
```
phpcpd .modman --log-pmd target/cpd.xml
```
```
phpcs --extensions=php --error-severity=6 --warning-severity=8 .modman --report=xml --report-file=target/checkstyle-result.xml
```
```
phpcs --extensions=php --error-severity=5 --warning-severity=8 --standard=Ecg .modman --report=xml --report-file=target/ecg.xml
```
```
phpcs --standard=/var/www/html/ecommage/coding-standard//EcgM2 /var/www/html/ecommage/niuniu/.modman/astralweb_backend_translate/ --report=xml --report-file=target/EcgM2.xml
```

## + MySQL ##
* Create Account Root
```bash
CREATE USER 'niuniudb'@'localhost' IDENTIFIED BY 'NBMOWt4NKEy9XurwueKJDp';
CREATE USER 'niuniudb'@'%' IDENTIFIED BY 'NBMOWt4NKEy9XurwueKJDp';
GRANT ALL PRIVILEGES ON niuniu.* TO 'niuniudb'@'localhost';
GRANT ALL ON niuniu.* TO 'niuniudb'@'%';
DROP USER 'niuniudb'@'139.162.38.199';
FLUSH PRIVILEGES;
```
* Reset AUTO_INCREMENT in MySQL?
```mysql
ALTER TABLE cron_schedule AUTO_INCREMENT = 1
```

# 2. **Ubuntu** #
## + zip ignore ##
```bash
zip -r product.zip  product/* --exclude=product/cache/*
zip -r niuniu_test.zip  niuniu/* --exclude=niuniu/pub/media/catalog/product/cache/*
```
php bin/magento module:disable Plumrocket_SocialLoginFree Plumrocket_Base AstralWeb_FacebookPixel Shopial_Facebook Mirasvit_Blog Mageplaza_SocialLogin