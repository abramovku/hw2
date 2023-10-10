# hw4
разворачиваем приложения 
````
helm install auth  ./apps/auth-app/auth-chart
helm install myapp  ./apps/user-app/crudapp-chart
````

установка traefik
````
helm repo add traefik https://helm.traefik.io/traefik
helm repo update

kubectl create namespace traefik

helm install --version "10.1.2" -n traefik -f apigw/traefik/traefik.yaml traefik traefik/traefik
````

Устанавливаем маршруты для traefik
````
kubectl apply -f apigw/traefik/routes.yaml
````

Forward routing
````
kubectl apply -f apigw/traefik/auth.yaml
````

Запускаем traefik 
````
minikube service -n traefik traefik
````


