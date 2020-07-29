FROM nginx

RUN addgroup --gid 1000 developer && \
    adduser --home /home/developer --shell /bin/bash  --disabled-login \
    --uid 1000 --gid 1000 developer && \
    usermod -aG nginx developer