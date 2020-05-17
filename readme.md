# Addresses Microservice

The addresses microservice is a project written in PHP using the Lumen Framework. It uses docker images for webserver (Nginx), database (MongoDB) and composer.

## Prerequisites 

This is a dockerized project, so the only depency is Docker and Docker Compose:

- [Docker](https://www.docker.com/get-started)

## Documentation

The objective of this project is to create a resource to search and save locally for addresses of Brazil using zipcode, and filter lists of states, cities, neighborhoods and zipcodes. Each list has his endpoint and for the response I used the JSON API specification that can be found [here](https://jsonapi.org/).

## Endpoints of project

- Search a address by zipcode

> zipcodes/{zipcode} (Example: zipcodes/11718020)

```json
{
    "data": {
        "type": "full_address",
        "id": "5ec07b78cae11943ab2c99b4",
        "attributes": {
            "zipcode": "11718-020",
            "complement": null
        },
        "relationships": {
            "address": {
                "data": {
                    "type": "addresses",
                    "id": "5ec07b78cae11943ab2c99b3",
                    "attributes": {
                        "name": "Rua Aurino Pereira Barbosa"
                    }
                }
            },
            "neighborhood": {
                "data": {
                    "type": "neighborhoods",
                    "id": "5ec069b9cae11943ab2c99b0",
                    "attributes": {
                        "name": "Anhanguera"
                    }
                }
            },
            "city": {
                "data": {
                    "type": "cities",
                    "id": "5ec05da01454c93cac45d7ad",
                    "attributes": {
                        "name": "Praia Grande"
                    }
                }
            },
            "state": {
                "data": {
                    "type": "states",
                    "id": "5ec05d94c854aa2e440864e5",
                    "attributes": {
                        "name": "São Paulo"
                    }
                }
            }
        }
    }
}
```

- List one or all states (this endpoint has a optional id parameter):

> /states

```json
{
    "data": [
        {
            "type": "states",
            "id": "5ec05d94c854aa2e440864d2",
            "attributes": {
                "name": "Rondônia",
                "initials": "RO"
            }
        },
        {
            "type": "states",
            "id": "5ec05d94c854aa2e440864d3",
            "attributes": {
                "name": "Acre",
                "initials": "AC"
            }
        },
        {
            "type": "states",
            "id": "5ec05d94c854aa2e440864d4",
            "attributes": {
                "name": "Amazonas",
                "initials": "AM"
            }
        },
        .
        .
        .
        .
    ]
}

```

> /states/{id}

```json
{
    "data": {
        "type": "states",
        "id": "5ec05d94c854aa2e440864e5",
        "attributes": {
            "name": "São Paulo",
            "initials": "SP"
        }
    }
}
```

- List all cities from a state

> /states/{stateId}/cities

```json
{
    "data": [
        {
            "type": "cities",
            "id": "5ec05da01454c93cac45d7ad",
            "attributes": {
                "name": "Praia Grande",
                "ibge_code": "3541000"
            }
        },
        {
            "type": "cities",
            "id": "5ec0638a1454c93cac45d7b0",
            "attributes": {
                "name": "São Paulo",
                "ibge_code": "3550308"
            }
        }
    ]
}
```

- List all neighborhoods from a city

> /cities/{cityId}/neighborhoods

```json
{
    "data": [
        {
            "type": "neighborhoods",
            "id": "5ec05da01454c93cac45d7ae",
            "attributes": {
                "name": "Maracanã"
            }
        },
        {
            "type": "neighborhoods",
            "id": "5ec069b9cae11943ab2c99b0",
            "attributes": {
                "name": "Anhanguera"
            }
        }
    ]
}
```

- List all addresses street from a neighborhood

> /neighborhoods/{neighborhoodId}/addresses

```json
{
    "data": [
        {
            "type": "addresses",
            "id": "5ec069b9cae11943ab2c99b1",
            "attributes": {
                "name": "Rua Josefa Alves de Siqueira"
            }
        },
        {
            "type": "addresses",
            "id": "5ec07b6d1454c93cac45d7b4",
            "attributes": {
                "name": "Rua Sebastiana Selite Agrela"
            }
        },
        {
            "type": "addresses",
            "id": "5ec07b78cae11943ab2c99b3",
            "attributes": {
                "name": "Rua Aurino Pereira Barbosa"
            }
        }
    ]
}
```

- List all zipcodes from a neighborhood

> /neighborhoods/{neighborhoodId}/zipcodes

```json
{
    "data": [
        {
            "type": "zipcodes",
            "id": "5ec069b9cae11943ab2c99b2",
            "attributes": {
                "complement": null,
                "number": "11718-000"
            }
        },
        {
            "type": "zipcodes",
            "id": "5ec07b6d1454c93cac45d7b5",
            "attributes": {
                "complement": null,
                "number": "11718-010"
            }
        },
        {
            "type": "zipcodes",
            "id": "5ec07b78cae11943ab2c99b4",
            "attributes": {
                "complement": null,
                "number": "11718-020"
            }
        }
    ]
}
```

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
