# Laravel Properties

*MiBo/laravel-properties*

[![codecov](https://codecov.io/gh/4513/laravel-properties/graph/badge.svg?token=p9C5HsHfsw)](https://codecov.io/gh/4513/laravel-properties)

(Mostly) Prices, VAT, Currencies but also other quantities and their units such as weight, length, area, volume,
time, speed, temperature, etc. are included in this library.

The library focuses on the ability to store, retrieve, calculate, convert, display and do other magic with
quantities and their units.

### Purpose
Beginning with prices and anything related to it:
1. **Creating** a price (**with or without VAT**);
2. Creating a **positive price** (a price that MUST NOT be negative);
3. **Calculating** prices (adding, subtracting);
4. **Converting** prices (to different currencies such as from CZK to EUR);
5. Converting **VAT rate** based on the country (checking VAT rate and its value among different countries);
6. **Displaying** prices (formatting the price to a specific locale and displaying correct currency name or its symbol);
7. Calculating prices with different currencies and/or VAT rates;
8. Working with an **exchange rate**;
9. Using **historical price** (price might have been created a time ago and a different VAT rate and/or value is used);
10. **Storing** price into database with **custom format**;
11. **Retrieving** price from database with custom format;
12. Creating **discounts** based on the provided price-able objects;
13. Retrieving **true VAT value of combined prices**;
14. Ability to have **custom currency** (internal web/app currency);
15. Price **per quantity** (e.g. price per 1 kilogram, piece, etc.);
16. **Full ISO currency** list (one can choose to use all the time updated source);

And to not end with the prices only:
1. Most common **quantities** and their **units** (such as length - meter, mile, inch, etc.);
2. **Converting** units (such as from meter to mile);
3. **SI prefixed** for units (for example kilo, milli);
4. **Metric**, **Imperial**, **US customary**, **SI units**;
5. Calculating quantities (adding, subtracting, **multiplying**, **dividing**);
6. **Derived** quantities (such as speed, acceleration, etc.);
7. **Displaying** quantities using a printer;
8. **Storing** and **retrieving** quantities into database with **custom format**;
9. **Creating** a quantity (**with specified or default unit**);
10. Access to **decimal** property (one can choose number of digits);
11. Access to more precise **float** property (optimized for calculations while thinking about the speed and memory);
12. Ability to **create custom quantity**, units or install additional ones;
13. **Support** for custom **not numerical quantities** (such as clothes size - *inDev*);
14. **Comparing** properties (such as whether are equal, greater, less, between, etc.);

and all of that and more for Laravel framework. Note, that most of these features are accessible out of
this library and can be used in any other PHP project. This library only collects them together and provides
additional features for Laravel and Eloquent, such as attribute casting for database, translations, and others.

### Installation
```bash
composer require mibo/laravel-properties

php artisan vendor:publish --provider="MiBo\Properties\Providers\ConfigProvider"
```

 One should first edit the configuration file `prices.php` and add a VAT Resolver and Convertor to be used. Without
them, the prices are zero-able only.

 To use comparing prices, rounding, ceiling or flooring them, one should install implementation of the
`\MiBo\Prices\Contracts\PriceCalculatorHelper` for rounding, and/or `\MiBo\Prices\Contracts\PriceComparer`
for comparing the prices, and set them up in the configuration file.

### Usage

#### Creating a price
 Trying to avoid using many classes to create one price, we created a Factory that can be used to create a price
with any required properties. With or without VAT? No problem. Specific VAT rate? Currency? That's all possible.  
 By default, the Factory uses default values from configuration file. Each calling of `::get()` method on the
Factory restores the default values to let the user create a new price, while keeping only one instance of the
Factory.
```php
$factory = \MiBo\Prices\Data\Factories\PriceFactory::get();

$factory->setValue(10) // The price value
    ->setCurrency('USD') // The currency that the price should have
    ->setDate(\Carbon\Carbon::now()->addYears(-1)) // The price might have been created a time ago
    ->setIsVATIncluded(true)
    ->create();
```
The example above would return a price that's total value is 10 USD including VAT => less than 10 USD without VAT,
while the following example would return a price that's total value is 10 USD without VAT => more than 10 USD
including VAT.
```php
$factory->setValue(10)
    ->setCurrency('USD')
    ->setDate(\Carbon\Carbon::now()->addYears(-1))
    ->setIsVATIncluded(false)
    ->create();
```
One can create an empty price with only default values (currency, VAT rate, current time):
```php
$factory->create();
```

 You might specify, that the price MUST NOT be negative. That might be helpful when you want to create a price
or calculate a price that normally should not be negative, such as a price of a purchase. This type of price
is considered invalid if its value is negative and throws an Error. The price is considered negative if at
least one of its price based on the VAT is negative.
```php
$factory->setValue(10)
    ->strictlyPositive()
    ->create();
```

#### Calculating prices
 Adding and subtracting prices are easy thanks to the direct methods on the prices themselves. One is not required
to use any additional classes to calculate them.
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->add($price); // Adding another or the same instance of the Price
$price->add(10); // Adding a number of int or float
$price->subtract($price); // Subtracting another or the same instance of the Price
$price->subtract(10.0); // Subtracting a number of int or float
```

 If int or float are provided, it is considered that the value is as the same unit (currency), VAT rate and date
as the current price, while the value is without VAT. User must be aware that adding or subtracting a float or
int from the price that's VAT rate is combined triggers an Error, because in such a case there is no information
what kind of price is expected to be added or subtracted.

**Different Currencies?**  
 When calculating multiple prices that do have different currency, the Exchanger is used to convert the prices
to the same currency. In that case, the current price is kept in its currency, while the given subject is converted
to the currency of the current price.  
 Such a way of calculating prices provides us more flexibility and no need to convert every price before calculating
them. The Exchanger is used only when it is needed.  

**Different VAT rates?**  
 Again, when calculating we want to combine only compatible subjects. Because of that, it is being checked that
both the prices have the same VAT rate. Firstly, the VAT country is changed to that of the current price. Next,
the VAT rate is being compared. If the VAT rate is different, the current price's VAT rate is set to 'COMBINED',
which represents a combination of two or more VAT rates. This solution let us calculate true value of the final
price and at the same time, allows us to still have the ability to convert the prices, either to different
currencies or VAT rates for another countries.

**More calculation required?**  
 Except of adding and subtracting, the price offers multiplying and dividing. Of course, multiplying price by
price does not make a sense, but multiplying can be made using
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->multiply(10); // Multiplying by int or float
```
 Or a price per a quantity is needed? Such as 10 EUR per 1 millimeter can be done! And another converting can be done
as well!
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->divide(\MiBo\Properties\Length::MILLI(1));
```

#### Converting
 To make sure we provide the final price in correct currency, one can call a method directly on the price:
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->convertToUnit(\MiBo\Prices\Units\Price\Currency::get('EUR'));
```
The method itself takes care to change the price's currency and to use the Exchanger to convert the price's value.

#### International selling
 Each country has its own VAT rates, with different categories, and the value of the VAT rate. Making sure
that the user fully avoids the need to check whether he or she calculates the prices correctly, the Price
object comes with a method to simply change VAT country. And after that, the `getPriceWithVAT` returns the
price with the VAT of that given country. Just like that:
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->forCountry('SVK');
```

#### Printer and locale
 Some countries have fancy way of displaying prices (no offense). To make sure that the price is displayed
correctly, the Price object comes with a Printer that can be set in configuration. There is an option to
use native PHP format based on the request, however that is not always the best option.
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->print(); // $10.00 / 10,00 € / or set up yours just like you want it!
```
 The printer can be called directly on the price or can be used as a standalone object and the price can be
parsed into it. The printer provides an ability to specify a decimal digit count.
 The library comes with all current currencies symbols and names of the currencies.

#### Historical Price
 When comes to purchases, that were made a year ago or so, the VAT rate might have changed. Not only the VAT
value, but even its rate and who knows what else. For that, the DateTime comes to prices and is always used
when checking the Price's true value. If the DateTime is not specified, the price is considered as the current
one.  
 To simplify, if the VAT for bread was 0 % in 2010 and 20 % now, and the price without VAT was always 10 EUR,
then the Price with specified time of year 2010 will return 10 EUR with VAT, while the price without specified
time or the time of year 2010 and later will return 22 EUR with VAT.

### Eloquent
 Hell! Prices are so complex with so much information, one might to ruin his/her database with so many columns.
Many of us do not need to store all the information about the price, because we might not want to use more than
one currency. We would like to use only current prices. Want to use minor units instead of major ones so there
is no need to use floats or decimals in the database.
 Solution for (I hope) everyone! *Just please, lemme know if not.*

`\MiBo\Prices\Data\Casting\PriceAttribute` joined the party and is configurable for everyone's need.

Lets consider a table `tbl_product` of a model MyModel:
```php
class MyModel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'tbl_product';

    protected $casts = [
        'price' => \MiBo\Prices\Data\Casting\PriceAttribute::class,
    ];
}
```
 The attribute caster changes the data of that model into `\MiBo\Prices\Price` object when the model is used
in the application, and stores all the price's information into the database, so the developer does not need
to take care of all the information. Be default, category for VAT `''`, current date, currency from the config,
country from the config are used and Price without VAT is created/stored. However:

#### Additional columns
 If the table comes with additional columns, the caster uses their values to create the Price object, and stores
the Price's information into those columns. To continue using the example above, lets add new columns in a migration:
```php
function(\Illuminate\Database\Schema\Blueprint $table) {
    $table->string('price_currency', 3); // Currency code of the price
    $table->string('price_country', 3); // Country code of the price
    $table->string('price_category', 8); // VAT category (classification) of the price
    $table->date('price_date'); // Date of the price
}
```
 Now, the caster will use those columns. The 'price' prefix of the column depends on the name of the attribute.

**Different column names (suffix)**  
 Sometimes we do want to rename the columns (for example to have 'price_cat' instead of 'price_category'). That
can be done by specifying the column names in the caster:
```php
    protected $casts = [
        'price' => \MiBo\Prices\Data\Casting\PriceAttribute::class . ':category-_cat',
    ];
```
In that example we tell the caster that for the category, suffix `_cat` should be used.

**Different column names (whole name)**  
 Date, currency and country might have a completely different column name that should be used. The main reason
behind that is that the price for a product or an order might use the time of creation instead of its own
column that would be a duplicated information. Note, that in that case, the caster does not change the information
within the column and rather changes or converts the price before storing. An example for using common `created_at`
column for the date of the price:
```php
    protected $casts = [
        'price' => \MiBo\Prices\Data\Casting\PriceAttribute::class . ':date-created_at',
    ];
```

#### Fixed values of the model
 Sometimes we do know that the currency for that particular model will always be same. The Caster allows us to
define currency, date, country and more for the model directly where the caster is set:
```php
    protected $casts = [
        'price' => \MiBo\Prices\Data\Casting\PriceAttribute::class . ':currency-EUR,country-SVK',
    ];
```
 In the example above, we tell the caster that all the prices are stored in euros and the country used for VAT
is Slovakia. The caster will use those values to create the correct value which is provided. When storing the
price, the caster makes sure that the values are stored correctly and performs a conversion if needed.

#### Settings of the Caster
 The settings come after a column (`:`) of the class name of the caster, where the keys and the values are separated
by a dash (`-`). The pairs are separated by a comma (`,`). The caster comes with the following settings:

* **Currency** (`currency`)
  * if not provided and the column does not exist, value from configuration is used;
  * if not provided, but the column with suffix `_currency` is used, that value is used to create the Price;
  * if provided string that begins with `_`, the column with that suffix is used to create the Price;
  * if provided string that does not begin with `_` and the column with that name exists, that value is used;
  * if provided string contains valid currency code of the ISO 4217, that value is used (`EUR`).
* **Positives** (`positive`)
  * if not provided, price that can be negative is created;
  * if specified, the price will be created as positive or negative (`true` or `false`).
  * Use `positive-true` to create only positive prices.
* **Category** (`category`)
  * if set closure via `setCategoryCallback()` method on the caster, that closure is used to get the category;
  * if not specified and the column with suffix `_category` exist, it is used;
  * if specified string that begins with `_` and the column with that suffix exists, that value is used;
  * if specified string, it is used as the category;
  * if nothing is valid, null or an empty string is used.
* **Country** (`country`)
  * if not specified and the column with suffix `_country` exists, that value is used;
  * if specified string with `_` and the column with that suffix exists, that value is used;
  * if specified string, it is used as the country (expected ISO 3166-1 alpha-3/2);
  * if nothing is valid, value from configuration is used.
* **Date** (`date`)
  * if not specified and the column with suffix `_date` exists, that value is used;
  * if specified string with `_` and the column with that suffix exists, that value is used (read-write);
  * if specified and the column exist, it is used (read only);
  * if specified in format `Y-m-d`, it is used as the date;
  * if nothing is valid, current date is used.
* **Any VAT rate** (`any`)
  * if not specified, the VAT rate is being used as a result of VAT Resolver;
  * if set to 'true', the VAT rate of ANY is set to the result.
* **With VAT** (`vat`)
  * if not specified, the value without VAT is stored and created;
  * if set to 'true', the value with VAT is stored and created.
* **Minor Unit** (`inMinor`)
  * if not specified, the value is stored in minor units of that currency (cents in EUR) - allows to have integers instead of floats, decimals;
  * if set to 'false', the value is stored in major units of that currency (euros in EUR).

### Discounts
 Discounts and their applying might be tricky. What VAT rate should be used when the discount is applied? How
to check what can be discounted and what not?

We offer a Factory that creates a discount with its price based on the settings and provided discountable objects.

 Discount of type percentage or fixed amount, with or without VAT, for specified VAT rate only or any, and much
more can be set using a 'setOption' on the factory.

```php
$factory  = \MiBo\Prices\Data\Factories\DiscountFactory::get();
$discount = $factory->setOption(\MiBo\Prices\Data\Factories\DiscountFactory::OPT_VALUE, 550)
    ->setOption(\MiBo\Prices\Data\Factories\DiscountFactory::OPT_SUBJECT, [$productList])
    ->create();
```

The factory provides the following options:
* `OPT_COUNTRY` to specify the country of the discount;
* `OPT_FILTER` to set up your filter for the list of objects that can be discounted;
* `OPT_IS_VALUE_WITH_VAT` want to use the provided value with VAT or without/Do you want to apply on a price with or without VAT?;
* `OPT_PERCENTAGE_VALUE` using percentage? Specify its value *(0-100)*;
* `OPT_REQUIRES_WHOLE_SUM_TO_USE` want to make sure that the discount is applied only if it is fully used?;
* `OPT_SUBJECT` what will be discounted? Provide a list of objects that can be discounted;
* `OPT_TYPE` type of percentage or fixed amount? Or a custom? Create your own discount type;
* `OPT_VALUE` value of the discount that can be used (e.g. 100 CZK);
* `OPT_VAT` VAT rate of the discount. Can be used for anything? Or only products with specified rate?

Add your own type using
```php
\MiBo\Prices\Data\Factories\DiscountFactory::customType(
    'your-type-name',
    function (
        iterable $subject,
        \MiBo\Prices\PositivePrice|\MiBo\Prices\PositivePriceWithVAT $discount,
        array $config
    ): \MiBo\Prices\PositivePrice|\MiBo\Prices\PositivePriceWithVAT {
       // your code
    }
);
\MiBo\Prices\Data\Factories\DiscountFactory::get()
    ->setOption(\MiBo\Prices\Data\Factories\DiscountFactory::OPT_TYPE, 'your-type-name')
    ->create();
```

### Currencies
 By default, ISO List loader for currencies is used. It loads the list that has all current currencies. However,
you can create, implement or use different loader. Why? Create your own currency for your application as a
benefit for your users. Update the Exchanger to convert your currency to another one and set its rate.

### Comparing
 *This feature requires an implementation of some interfaces and their configuring in the config file.*
 Try to avoid getting a value of a price and comparing it with another one. Especially when comparing two
float values, the result might be unexpected. Use common interface of the price. It provides all needed methods
and their negation, so you can compare two prices without getting their values.
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->isBetween(10, $price);
$price->isLessThan($price);
$price->isGreaterThanOrEqualTo($price);
$price->isZero();
$price->isNegative();
$price->is($price);
$price->hasSameValueAs($price);
$price->hasNotSameValueWithVATAs($price);
```
You can mostly use either price object or float/int. And, you can round, ceil and floor the value. For each of
these, you can specify the precision (yep, for floor and ceil too!):
```php
/** @var \MiBo\Prices\Price $price */
$price = {...};
$price->ceil(2);
$price->floor(-2);
$price->round(2, PHP_ROUND_HALF_DOWN);
```


### Translations
Most (or all) of the properties from library mibo/properties and property of Price have translations in this
library.  
**Note**: *Translations are not being SemVered and any fix of an existing translation, even tho it is changed,
is considered as a patch. New translations (for both, new locale and/or new property) are considered as a minor
change.*

Each property has separated translation file, which is called by the property's quantity's translation name,
specified in `getNameForTranslation()` method. Common return value consists of 'name' key, which is the translated
name of the property, 'units' key, which is a list of available units for the quantity. Each unit is keyed by
its code/symbol. The value contains a 'name' (translated name of the unit for integer value), 'name-float'
(translated name of the unit for float value - some languages have different names for integer and float values),
symbol (symbol of the unit). There is a 'format' key within the property's translation, which contains a format
used to format the value of the property - 'short' and 'long'. The format is used, because some languages have
different formats for e.g. prices ($10 for en_US; 10 $ for cs_CZ).
**Note**: *Format 'short' for Degree Celsius is ignored and (should) always result into 1°C (without a space).* 
