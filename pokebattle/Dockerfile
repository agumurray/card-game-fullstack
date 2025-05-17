FROM node:23-alpine

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .

EXPOSE 3000

CMD [ "sh", "-c", "rm -rf node_modules && npm install && npm run dev -- --host" ]
