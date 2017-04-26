# user-service
User authentication microservice

# To spin up the environment:
docker-compose build<br>
docker-compose up -d

# Endpoints:
| Endpoint                                    | Params                         |
| ------------------------------------------- | ------------------------------ |
| PUT /user (register a new user)             | name, email, password |
| POST /user (authenticate a user)            | email, password       |
| GET /user/{id} (view the details of a user) | id                    |

