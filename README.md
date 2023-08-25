# hw2

```bash
cd k8s
kubectl apply -f app-config.yaml -f secrets.yaml -f deployment.yaml -f postgres.yaml
```
Миграция
```bash
kubectl apply -f job.yaml
```
