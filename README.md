# E-commerce Microservices System (Laravel + MySQL + Docker)

This repository demonstrates a simple **microservices architecture** using **Laravel**, **MySQL**, and **Docker**.
It contains three independent services:

1. **Catalog Service** – Manages and lists products.
2. **Checkout Service** – Handles orders, validates products, and triggers confirmation emails.
3. **Email Service** – Sends order confirmation emails via **MailHog** (for testing).

---

## 🧩 Architecture

Each service runs in its own Docker container with its own MySQL database.

```
Client → Checkout → Catalog → Email → MailHog
```

* **Catalog** exposes endpoints to list and show products.
* **Checkout** consumes Catalog APIs to validate product details and calls Email Service.
* **Email** sends confirmation emails (captured by **MailHog** for testing).

---

## ⚙️ Prerequisites

Before running this project, ensure you have:

* [Docker](https://www.docker.com/) & Docker Compose installed
* [Composer](https://getcomposer.org/) installed (optional, for local Laravel setup)
* Git installed
* (Optional) AWS account for EC2 or CloudFormation deployment

---

## 🚀 Provisioning / Setup

### 1️⃣ Clone the repository

```bash
git clone https://github.com/<your-username>/ecommerce-microservices.git
cd ecommerce-microservices
```

### 2️⃣ Build and start the containers

```bash
docker compose build
docker compose up -d
```

Services running locally:

| Service  | URL                                            | Description                  |
| -------- | ---------------------------------------------- | ---------------------------- |
| Catalog  | [http://localhost:8001](http://localhost:8001) | Lists available products     |
| Checkout | [http://localhost:8002](http://localhost:8002) | Accepts and processes orders |
| Email    | [http://localhost:8003](http://localhost:8003) | Sends confirmation emails    |
| MailHog  | [http://localhost:8025](http://localhost:8025) | Web UI to view test emails   |

---

## 🧾 Configuration

Each service has its own `.env` file (copied from `.env.example`) and connects to its own MySQL container.

Example for **Catalog Service** (`catalog-service/.env`):

```env
DB_CONNECTION=mysql
DB_HOST=catalog-db
DB_PORT=3306
DB_DATABASE=catalog
DB_USERNAME=user
DB_PASSWORD=password
```

Example for **Checkout Service** (`checkout-service/.env`):

```env
DB_CONNECTION=mysql
DB_HOST=checkout-db
DB_PORT=3306
DB_DATABASE=checkout
DB_USERNAME=user
DB_PASSWORD=password
```

Example for **Checkout Service**, add other services’ URLs (`checkout-service/.env`):

```env
CATALOG_URL=http://catalog-service:8000/api
EMAIL_URL=http://email-service:8000/api
```

For **Email Service**, configure **MailHog** (`email-service/.env`):

If running locally via Docker:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog          # Service name from docker-compose.yml
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="Ecommerce Platform"
```

If using a private IP (e.g., in ECS or remote setup):

```env
MAIL_MAILER=smtp
MAIL_HOST=10.0.1.35        # Replace with MailHog private IP
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@ecommerce.local
MAIL_FROM_NAME="Ecommerce Platform"
```

Example for **Frontend Service**, configure URLs (`frontend/.env`):

```env
VITE_APP_CATALOG_URL=http://localhost:8001/api
VITE_APP_CHECKOUT_URL=http://localhost:8002/api
```

---

## 🧠 Database Setup

Once containers are running, run migrations and seeders:

```bash
docker exec -it catalog-service php artisan migrate --seed
docker exec -it checkout-service php artisan migrate
docker exec -it email-service php artisan migrate
```

Catalog service will seed sample products from [ProductSeeder.php](./catalog-service/database/seeders/ProductSeeder.php).

---

## 🧩 API Endpoints

### 📦 Catalog Service

| Method | Endpoint             | Description          |
| ------ | -------------------- | -------------------- |
| GET    | `/api/products`      | List all products    |
| GET    | `/api/products/{id}` | Show product details |

### 🛒 Checkout Service

| Method | Endpoint      | Description      |
| ------ | ------------- | ---------------- |
| GET    | `/api/orders` | List all orders  |
| POST   | `/api/orders` | Create new order |

**Sample POST /api/orders request:**

```json
{
  "email": "john@example.com",
  "products": [
    { "id": 1, "quantity": 1 }
  ]
}
```

Response:

```json
{
  "order_id": 1,
  "products": [
    {
      "id": 1,
      "name": "Gaming Laptop",
      "price": "1500.00",
      "quantity": 1,
      "subtotal": 1500
    }
  ],
  "total": 1500.0,
  "status": "Email sent to customer"
}
```

### 📧 Email Service

| Method | Endpoint    | Description                   |
| ------ | ----------- | ----------------------------- |
| POST   | `/api/send` | Send order confirmation email |

Sample payload:

```json
{
  "order_id": 1,
  "send_to": "john@example.com",
  "products": [
    { "id": 1, "name": "Gaming Laptop", "price": "1500.00", "quantity": 1, "subtotal": 1500 }
  ],
  "total": 1500.0
}
```

Response:

```json
{ "status": "Email sent" }
```

---

## 🧪 Testing the System

For detailed frontend testing steps, see the [Frontend Testing Guide](./docs/FRONTEND_TESTING.md).

1. **List Products**

   ```bash
   curl http://localhost:8001/api/products
   ```

2. **Place an Order**

   ```bash
   curl -X POST http://localhost:8002/api/orders \
     -H "Content-Type: application/json" \
     -d '{ "email": "john@example.com", "products": [ { "id": 1, "quantity": 1 } ] }'
   ```

3. **Check Email Logs (MailHog)**

   Open [http://localhost:8025](http://localhost:8025) to view the test email.

---

## ☁️ AWS Deployment (CloudFormation)

This project can be deployed entirely using **AWS CloudFormation**.
For detailed deployment steps, see the [CloudFormation Deployment Guide](./docs/CLOUD_FORMATION_README.md).

### 1️⃣ Ensure prerequisites

* AWS CLI installed and configured
* Docker installed locally (optional if building locally before deployment)
* CloudFormation template: `microservices-stack.yaml`

### 2️⃣ Setup Environment Files

Create an S3 bucket called `envs-cloudformation` and upload the `.env` files:

* `catalog.env`
* `checkout.env`
* `email.env`

These files will be used by the containers after deployment.

---

### 3️⃣ Create CloudFormation IAM Role

```bash
aws iam create-role \
  --role-name CloudFormationExecutionRole \
  --assume-role-policy-document file://CloudFormationExecutionRole-TrustPolicy.json

aws iam put-role-policy \
  --role-name CloudFormationExecutionRole \
  --policy-name CloudFormationExecutionPolicy \
  --policy-document file://CloudFormationExecutionRole-Policy.json
```

---

## 🏗️ Future Enhancements

* Add **RabbitMQ or Redis queues** for async email processing
* Add **Authentication (JWT)** for user management

---

## 🧑‍💻 Author

Developed by [Ronnel Mungcal]
📧 [rnnlmungcal@gmail.com](mailto:rnnlmungcal@gmail.com)
🌐 [https://github.com/RnnlMungcal](https://github.com/RnnlMungcal)

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE). 