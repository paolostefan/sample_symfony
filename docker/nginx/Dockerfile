FROM nginx

RUN groupadd --gid 1000 developer && \
    useradd -d /home/developer -ms /bin/bash -u 1000 -g 1000 -G nginx developer
