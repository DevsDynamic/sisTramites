FROM maven:3.9-eclipse-temurin-21 AS build
WORKDIR /build
COPY pom.xml .
RUN mvn -q -DskipTests dependency:go-offline
COPY src ./src
RUN mvn -q -DskipTests package

FROM eclipse-temurin:21-jre
RUN useradd --system --create-home signer
WORKDIR /app
COPY --from=build /build/target/internal-pades-signer-1.0.0.jar app.jar
USER signer
EXPOSE 8080
ENTRYPOINT ["java", "-jar", "/app/app.jar"]
