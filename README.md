# hw2

```bash
cd k8s
kubectl apply -f app-config.yaml -f secrets.yaml -f app.yaml -f postgres.yaml
```
Миграция
```bash
kubectl apply -f job.yaml
```

Ингресс
```bash
kubectl apply -f ingress.yaml
```
