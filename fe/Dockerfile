FROM node:18.16.0-alpine
WORKDIR /app/fe
COPY . .
RUN npm install
RUN npm run build
EXPOSE 3000
ENTRYPOINT npm start