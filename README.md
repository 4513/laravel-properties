# Laravel Properties

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

php artisan vendor:publish --provider="MiBo\Prices\Providers\ConfigProvider"
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

#### Eloquent
 Hell! Prices are so complex with so much information, one might to ruin his/her database with so many columns.
Many of us do not need to store all the information about the price, because we might not want to use more than
one currency. We would like to use only current prices, and I dunno what.
 Solution for (I hope) everyone! *Just please, lemme know if not.*

`\MiBo\Prices\Data\Casting\PriceAttribute` joined the party and is configurable for everyone's need.

## Schemas
Product has a price.

### Price schemas

Tbl Product (simple price)
* being used when the application uses only one currency and VAT rate is always for the same country;
* VAT category can have a separated column or can be specified within the Model;
* currency is loaded as a default from configuration file.

| Product ID (PK) | Price Value |
|-----------------|-------------|
| 1               | 10          |
| 2               | 10          |

Tbl Product & Tbl Price (multi currency)
* used when the product might have different currencies;
* prices are stored in a separated table;
* VAT category is specified within Product Model and is located within the Product's table;
* applicable for those who sell products in different currencies within the same country.

| Product ID (FK) | Price Value | Currency Code |
|-----------------|-------------|---------------|
| 1               | 10          | USD           |
| 1               | 20          | CZK           |
| 2               | 10          | USD           |
| 2               | 8           | EUR           |

Tbl Product & Tbl PriceLog
* combinable with both previous schemas;
* used when the application needs to store the price history;
* VAT category can be retrieved correctly by the timestamp of the price change;
* one can use the table to store final price of an entity of a specific time;
* application can use the table as a log of price changes.

| Product ID (FK) | Price Value | Currency Code | Timestamp  |
|-----------------|-------------|---------------|------------|
| 1               | 10          | USD           | 2019-01-01 |
| 1               | 20          | CZK           | 2019-01-01 |
| 2               | 10          | USD           | 2019-01-01 |
| 2               | 8           | EUR           | 2019-01-01 |
| 1               | 12          | USD           | 2022-01-01 |

Tbl Product w Log inside
* the table contains a timestamp of the creation time of that entity, which is used as a timestamp for VAT;
* applicable for retrieving exact price of a sold product for specified time.

| Product ID (PK) | Price Value | Currency Code | Timestamp   |
|-----------------|-------------|---------------|-------------|
| 1               | 10          | USD           | 2019-01-01  |
| 2               | 10          | USD           | 2019-01-01  |
| 1               | 12          | USD           | 2022-01-01  |

### VAT schemas
Event tho VAT belongs to a price, the VAT is not based on the price (not being meant its value, but the VAT rate).  
The VAT rate is based on the country and the classification of the product. There are many classification
systems and a few of them have a specific categories, so no product changes its category.  
Over a time, the classification category might change its VAT rate and, the VAT rate might change its percentage
value.  
Because of both of the above, the VAT rate is separated from product and price table. That allows us to get
the very true price and its value with VAT for a specific time.  

Tbl VAT

| Category ID (FK) (UK category country) | Country Code (UK category country) | VAT Rate | Timestamp   |
|----------------------------------------|------------------------------------|----------|-------------|
| 1                                      | CZ                                 | STANDARD | 2019-01-01  |
| 1                                      | CZ                                 | REDUCED  | 2022-01-01  |
| 2                                      | CZ                                 | 10       | 2019-01-01  |

Tbl VAT Rate
* maximum number of rows within this table is number of countries multiplied by 5 (types of VAT rates) multiplied
  by count of changes of VAT rates for specific country;

| Country Code (PK) | VAT Rate (PK)  | Percentage Value (PK) | Timestamp (PK) |
|-------------------|----------------|-----------------------|----------------|
| CZ                | STANDARD       | 21                    | 2019-01-01     |
| CZ                | REDUCED        | 15                    | 2022-01-01     |
| CZ                | SECOND_REDUCED | 10                    | 2019-01-01     |
| CZ                | NONE           | 0                     | 2019-01-01     |
| SK                | STANDARD       | 20                    | 2019-01-01     |
| SK                | REDUCED        | 10                    | 2019-01-01     |
| SK                | NONE           | 0                     | 2019-01-01     |

Tbl Product
* the table contains a reference to a category classification of the product;
* user might use a product ID as a category ID instead, but that loses the ability to categorize multiple
  products into one category and simply use their shared information.

| Product ID (PK) | Category ID (FK) |
|-----------------|------------------|
| 1               | 1                |
| 2               | 2                |
| 3               | 1                |

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
