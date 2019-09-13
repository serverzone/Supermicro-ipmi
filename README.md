# Supermicro IPMI Wrapper

This library is GuzzleHttp based control class for Supermicro servers.

Our main motivation to write web based wrapper class was lack of support of ipmi protocol over IPv6.
As we move towards IPv6 only network, we wanted to get rid of IPv4 addresses on our management cards.
Supermicro however supports web interface over IPv6, which is how this library was created.

Please, use this library only on older servers without redfish support. Redfish protocol should fix the
problem with deprecated IPMI protocol and should already be accessible via IPv6.

## Instalation

```
composer require serverzone/supermicro-ipmi
```

## Usage

### Inicialization

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$instance = new ServerZone\SupermicroIpmi\Client('my-ipmi.local', 'ADMIN', 'ADMIN');
$instance = new ServerZone\SupermicroIpmi\Client('192.168.0.1', 'ADMIN', 'ADMIN');
$instance = new ServerZone\SupermicroIpmi\Client('[2001:db8::1]', 'ADMIN', 'ADMIN');
```

### Power status

```php
var_dump($instance->getPowerStatus());
```

### Power consumption reading

```php
var_dump($i->getPowerConsumption());
```

### Power on

```php
$instance->powerOn();
```

### Power off

```php
$instance->powerOff();
```

# Power reset

```php
$instance->powerReset();
```

### Sensors reading

```php
foreach($i->getSensors() as $row) {
    printf("%20s %8s %10s %10s\n", $row->getName(), $row->getValue(), $row->getUnits(), $row->getStatus(), $row->getLimit('UNC'));
}
```

## Testing

This library has been tested on several servers we had available:

* X9SCL-F
* X9SRW-F
* X11SSL-F
* And several others

## Contribution

Feel free to send us merge requests, we will be happy to merge them.
