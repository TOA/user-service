# user-service
User authentication microservice

# To spin up the environment:
docker-compose build<br>
docker-compose up -d

# Endpoints:
| Endpoint                                    | Params                         |
| ------------------------------------------- | ------------------------------ |
| POST /user - register a new user            | params - name, email, password |
| PUT /user - authenticate a user             | params - email, password       |
| GET /user/{id} - view the details of a user | params - id                    |

