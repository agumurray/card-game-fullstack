FROM node:23-alpine

WORKDIR /app

# Copia solo los archivos necesarios para instalar deps
COPY package.json package-lock.json ./

RUN npm install

# Copia el resto del proyecto
COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev", "--", "--host"]
