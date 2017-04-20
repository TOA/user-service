# user-service
User authentication microservice

# To spin up the environment:
docker-compose build<br>
docker-compose up -d

# Endpoints:
| Endpoint                                    | Params                         |
| ------------------------------------------- | ------------------------------ |
| POST /user (register a new user)            | name, email, password |
| PUT /user (authenticate a user)             | email, password       |
| GET /user/{id} (view the details of a user) | id                    |

