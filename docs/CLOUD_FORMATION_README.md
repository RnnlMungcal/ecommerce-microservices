# E-commerce Microservices Deployment

This project contains multiple microservices (Catalog, Checkout, Email, Frontend) that can be deployed using **AWS CloudFormation** and Docker.

---

## ☁️ AWS Deployment (CloudFormation)

This project can be deployed entirely using **AWS CloudFormation** and ECR for Docker images.

> **🧩 Note:**
>
> - Replace `<ACCOUNT_ID>` with your AWS Account ID (found in the AWS Console under _My Account_).
> - Replace `<REGION>` with the AWS region you want to deploy to (e.g., `us-east-1`).

---

### 1️⃣ Setup Environment Files

Create an S3 bucket called `envs-cloudformation` and upload the `.env` files:

- `catalog.env`
- `checkout.env`
- `email.env`

These files will be used by the containers after deployment.

---

### 2️⃣ Create CloudFormation IAM Role

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

### 3️⃣ Create ECR Repositories

```bash
aws ecr create-repository --repository-name catalog-service --region <REGION>
aws ecr create-repository --repository-name checkout-service --region <REGION>
aws ecr create-repository --repository-name email-service --region <REGION>
aws ecr create-repository --repository-name frontend --region <REGION>
```

---

### 4️⃣ Authenticate Docker to ECR

```bash
aws ecr get-login-password --region <REGION> | docker login --username AWS --password-stdin <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com
```

---

### 5️⃣ Build Docker Images

```bash
(cd catalog-service && docker build -f Dockerfile.prod -t catalog-service:prod .) && \
(cd checkout-service && docker build -f Dockerfile.prod -t checkout-service:prod .) && \
(cd email-service && docker build -f Dockerfile.prod -t email-service:prod .) && \
(cd frontend && docker build -f Dockerfile.prod -t frontend:prod .)
```

---

### 6️⃣ Tag Docker Images

```bash
docker tag catalog-service:prod <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/catalog-service:latest
docker tag checkout-service:prod <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/checkout-service:latest
docker tag email-service:prod <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/email-service:latest
docker tag frontend:prod <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/frontend:latest
```

---

### 7️⃣ Push Docker Images to ECR

```bash
docker push <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/catalog-service:latest
docker push <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/checkout-service:latest
docker push <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/email-service:latest
docker push <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/frontend:latest
```

---

### 8️⃣ Deploy CloudFormation Stack

```bash
aws cloudformation create-stack \
  --stack-name ecommerce-microservices \
  --template-body file://microservices-stack.yaml \
  --parameters ParameterKey=DbUsername,ParameterValue=ecommerce_user ParameterKey=DbPassword,ParameterValue=hURARIsOYdOu \
  --capabilities CAPABILITY_IAM \
  --role-arn arn:aws:iam::<ACCOUNT_ID>:role/CloudFormationExecutionRole \
  --region <REGION>
```

---

### 9️⃣ Update Environment URLs & Rebuild Services

#### 1️⃣ Update Checkout `.env`

Update the **checkout service** `.env` file with the IP addresses of the deployed containers:

```env
# checkout.env
CATALOG_URL=http://54.172.205.57:8000/api
EMAIL_URL=http://52.203.7.206:8000/api
```

Force deployment to apply the updated `.env`:

```bash
aws ecs update-service \
  --cluster ecommerce-cluster \
  --service checkout-service \
  --force-new-deployment
```

---

#### 2️⃣ Update Frontend `.env`

Before rebuilding frontend, update your **local `.env`** file with the current container IPs:

```env
# frontend/.env
VITE_APP_CATALOG_URL=http://54.172.205.57:8000/api
VITE_APP_CHECKOUT_URL=http://3.86.254.205:8000/api
```

Rebuild and push the frontend with the updated `.env`:

```bash
(cd frontend && docker build -f Dockerfile.prod -t frontend:prod .)
docker tag frontend:prod <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/frontend:latest
docker push <ACCOUNT_ID>.dkr.ecr.<REGION>.amazonaws.com/frontend:latest
```

---

> 🔹 **Next Step:** Create new ECS **task definitions** and continue deployment as per this [Frontend Service Rebuild Steps](./FRONTEND_REBUILD.md)

---

### 🔹 Run Migrations and Seeders

> **🧩 Note:**
> Replace `<catalog-task-arn>`, `<checkout-task-arn>`, and `<email-task-arn>` with the **actual ECS task ARNs**.
> You can get them by running:
>
> ```bash
> aws ecs list-tasks --cluster ecommerce-cluster --service-name catalog-service --region <REGION>
> aws ecs list-tasks --cluster ecommerce-cluster --service-name checkout-service --region <REGION>
> aws ecs list-tasks --cluster ecommerce-cluster --service-name email-service --region <REGION>
> ```
>
> Then copy the ARN(s) from the output for use in the commands below.

```bash
aws ecs execute-command --cluster ecommerce-cluster --task <catalog-task-arn> --container catalog --command "php artisan migrate" --interactive --region <REGION>
aws ecs execute-command --cluster ecommerce-cluster --task <catalog-task-arn> --container catalog --command "php artisan db:seed --class=ProductSeeder" --interactive --region <REGION>
aws ecs execute-command --cluster ecommerce-cluster --task <checkout-task-arn> --container checkout --command "php artisan migrate" --interactive --region <REGION>
aws ecs execute-command --cluster ecommerce-cluster --task <email-task-arn> --container email --command "php artisan migrate" --interactive --region <REGION>
```

---

### ✅ Access the Application

After all services are deployed and migrations are run, access the frontend using the public URL from your load balancer or EC2 instance.
