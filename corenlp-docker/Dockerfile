# Stanford CoreNLP Docker Configuration
# Supports English and Spanish models
FROM eclipse-temurin:11-jre

# Set environment variables
ENV CORENLP_VERSION=4.5.7
ENV CORENLP_HOME=/opt/corenlp
ENV JAVA_OPTS="-Xmx2g"

# Install wget and unzip
RUN apt-get update && apt-get install -y --no-install-recommends \
    wget \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Create CoreNLP directory
WORKDIR ${CORENLP_HOME}

# Download CoreNLP main package
RUN wget -q https://nlp.stanford.edu/software/stanford-corenlp-${CORENLP_VERSION}.zip \
    && unzip stanford-corenlp-${CORENLP_VERSION}.zip \
    && mv stanford-corenlp-${CORENLP_VERSION}/* . \
    && rmdir stanford-corenlp-${CORENLP_VERSION} \
    && rm stanford-corenlp-${CORENLP_VERSION}.zip

# Download Spanish models
RUN wget -q https://nlp.stanford.edu/software/stanford-corenlp-${CORENLP_VERSION}-models-spanish.jar

# Expose the default CoreNLP server port
EXPOSE 9000

# Set the classpath to include all JARs
ENV CLASSPATH="${CORENLP_HOME}/*"

# Start CoreNLP Server
CMD java ${JAVA_OPTS} -cp "${CORENLP_HOME}/*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer \
    -port 9000 \
    -timeout 60000 \
    -threads 2 \
    -maxCharLength 100000 \
    -preload tokenize,ssplit,pos
