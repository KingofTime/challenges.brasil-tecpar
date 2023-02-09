CREATE DATABASE IF NOT EXISTS `brasiltecparchallenge`;
CREATE DATABASE IF NOT EXISTS `brasiltecparchallenge_test`;

GRANT ALL PRIVILEGES ON brasiltecparchallenge.* TO 'app'@'%';
GRANT ALL PRIVILEGES ON brasiltecparchallenge_test.* TO 'app'@'%';

FLUSH PRIVILEGES;