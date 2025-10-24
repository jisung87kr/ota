FROM node:20-alpine

WORKDIR /app

# Install dependencies
RUN apk add --no-cache git

# Expose Vite dev server port
EXPOSE 5173

CMD ["sh", "-c", "npm install && npm run dev -- --host"]
