/****** Object:  Database [SalesSystemDB]    Script Date: 4/23/2022 10:28:36 PM ******/
CREATE DATABASE [SalesSystemDB]  (EDITION = 'GeneralPurpose', SERVICE_OBJECTIVE = 'GP_S_Gen5_2', MAXSIZE = 32 GB) WITH CATALOG_COLLATION = SQL_Latin1_General_CP1_CI_AS;
GO
ALTER DATABASE [SalesSystemDB] SET COMPATIBILITY_LEVEL = 150
GO
ALTER DATABASE [SalesSystemDB] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [SalesSystemDB] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [SalesSystemDB] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [SalesSystemDB] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [SalesSystemDB] SET ARITHABORT OFF 
GO
ALTER DATABASE [SalesSystemDB] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [SalesSystemDB] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [SalesSystemDB] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [SalesSystemDB] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [SalesSystemDB] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [SalesSystemDB] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [SalesSystemDB] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [SalesSystemDB] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [SalesSystemDB] SET ALLOW_SNAPSHOT_ISOLATION ON 
GO
ALTER DATABASE [SalesSystemDB] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [SalesSystemDB] SET READ_COMMITTED_SNAPSHOT ON 
GO
ALTER DATABASE [SalesSystemDB] SET  MULTI_USER 
GO
ALTER DATABASE [SalesSystemDB] SET ENCRYPTION ON
GO
ALTER DATABASE [SalesSystemDB] SET QUERY_STORE = ON
GO
ALTER DATABASE [SalesSystemDB] SET QUERY_STORE (OPERATION_MODE = READ_WRITE, CLEANUP_POLICY = (STALE_QUERY_THRESHOLD_DAYS = 30), DATA_FLUSH_INTERVAL_SECONDS = 900, INTERVAL_LENGTH_MINUTES = 60, MAX_STORAGE_SIZE_MB = 100, QUERY_CAPTURE_MODE = AUTO, SIZE_BASED_CLEANUP_MODE = AUTO, MAX_PLANS_PER_QUERY = 200, WAIT_STATS_CAPTURE_MODE = ON)
GO
/*** The scripts of database scoped configurations in Azure should be executed inside the target database connection. ***/
GO
-- ALTER DATABASE SCOPED CONFIGURATION SET MAXDOP = 8;
GO
/****** Object:  Table [dbo].[Cart]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Cart](
	[ItemID] [int] NULL,
	[Quantity] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Categories]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Categories](
	[CtgID] [int] NOT NULL,
	[CtgName] [varchar](30) NULL,
PRIMARY KEY CLUSTERED 
(
	[CtgID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Customers]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Customers](
	[UUID] [int] IDENTITY(100000,1) NOT NULL,
	[FirstName] [varchar](20) NULL,
	[LastName] [varchar](30) NULL,
	[PhoneNumber] [varchar](15) NULL,
	[Email] [varchar](30) NULL,
	[Address] [varchar](30) NULL,
	[AddressZip] [int] NULL,
	[AddressState] [varchar](2) NULL,
	[Start_DtTm] [datetime] NULL,
	[IsActive] [bit] NULL,
	[AddressCity] [varchar](30) NULL,
PRIMARY KEY CLUSTERED 
(
	[UUID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY],
UNIQUE NONCLUSTERED 
(
	[PhoneNumber] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[EmailQueue]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[EmailQueue](
	[Email] [varchar](50) NULL,
	[FirstName] [varchar](50) NULL,
	[LastName] [varchar](50) NULL,
	[Body] [varchar](100) NULL,
	[EmailID] [int] IDENTITY(1,1) NOT NULL,
	[CustomerID] [int] NULL,
 CONSTRAINT [PK_EmailQueue_EmailID] PRIMARY KEY CLUSTERED 
(
	[EmailID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Employees]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Employees](
	[EID] [int] IDENTITY(100000,1) NOT NULL,
	[FirstName] [varchar](30) NULL,
	[LastName] [varchar](30) NULL,
	[PhoneNumber] [varchar](15) NULL,
	[Email] [varchar](30) NULL,
	[Position] [int] NULL,
	[Start_Dt] [date] NULL,
	[End_Dt] [date] NULL,
	[IsActive] [bit] NULL,
	[Username] [varchar](30) NULL,
	[Password] [varchar](max) NULL,
PRIMARY KEY CLUSTERED 
(
	[EID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY],
 CONSTRAINT [EMP_Username] UNIQUE NONCLUSTERED 
(
	[Username] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY],
UNIQUE NONCLUSTERED 
(
	[Username] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Inventory]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Inventory](
	[UPC] [int] IDENTITY(1000000000,1) NOT NULL,
	[StockQty] [int] NULL,
	[SoldQty] [int] NULL,
	[MinQty] [int] NULL,
	[Name] [varchar](30) NULL,
	[Description] [varchar](100) NULL,
	[Price] [money] NULL,
	[Category] [int] NULL,
	[Size] [varchar](5) NULL,
	[IsActive] [bit] NULL,
PRIMARY KEY CLUSTERED 
(
	[UPC] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[LowStock]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[LowStock](
	[UPC] [int] NULL,
	[Name] [varchar](40) NULL,
	[Size] [varchar](10) NULL,
	[StockQty] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SalesPositions]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SalesPositions](
	[PositionID] [int] IDENTITY(1,1) NOT NULL,
	[PositionAbbv] [varchar](10) NULL,
	[PositionTitle] [varchar](40) NULL,
	[HourlyWage] [money] NULL,
PRIMARY KEY CLUSTERED 
(
	[PositionID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Sessions]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Sessions](
	[EID] [int] NULL,
	[EMP] [bit] NULL,
	[MGR] [bit] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TransactionItems]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TransactionItems](
	[TransactionItemID] [int] NULL,
	[TransactionID] [int] NULL,
	[Quantity] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Transactions]    Script Date: 4/23/2022 10:28:36 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Transactions](
	[OrderedBy] [int] NULL,
	[TypeOfTransaction] [varchar](10) NULL,
	[TransactionDate] [datetime] NULL,
	[ProcessedBy] [int] NULL,
	[TransactionID] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[TransactionID] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[Cart]  WITH CHECK ADD FOREIGN KEY([ItemID])
REFERENCES [dbo].[Inventory] ([UPC])
GO
ALTER TABLE [dbo].[Employees]  WITH CHECK ADD FOREIGN KEY([Position])
REFERENCES [dbo].[SalesPositions] ([PositionID])
GO
ALTER TABLE [dbo].[Inventory]  WITH CHECK ADD FOREIGN KEY([Category])
REFERENCES [dbo].[Categories] ([CtgID])
GO
ALTER TABLE [dbo].[LowStock]  WITH CHECK ADD FOREIGN KEY([UPC])
REFERENCES [dbo].[Inventory] ([UPC])
GO
ALTER TABLE [dbo].[TransactionItems]  WITH CHECK ADD FOREIGN KEY([TransactionItemID])
REFERENCES [dbo].[Inventory] ([UPC])
GO
ALTER TABLE [dbo].[TransactionItems]  WITH CHECK ADD FOREIGN KEY([TransactionID])
REFERENCES [dbo].[Transactions] ([TransactionID])
GO
ALTER TABLE [dbo].[Transactions]  WITH CHECK ADD FOREIGN KEY([OrderedBy])
REFERENCES [dbo].[Customers] ([UUID])
GO
ALTER TABLE [dbo].[Transactions]  WITH CHECK ADD FOREIGN KEY([ProcessedBy])
REFERENCES [dbo].[Employees] ([EID])
GO
ALTER DATABASE [SalesSystemDB] SET  READ_WRITE 
GO
