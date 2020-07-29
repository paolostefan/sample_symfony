FROM nginx

RUN addgroup --gid 1000 developer && \
    useradd --uid 1000 --gid 1000 developer && \
    usermod -aG nginx developer

