-- Admin View
CREATE VIEW old_admin AS
SELECT 
    a.id AS ID,
    u.username AS Username,
    u.password AS Password,
    a.AccessLevel AS AccessLevel
FROM admin a
JOIN users u ON a.user_id = u.ID;

-- Users View
CREATE VIEW old_users AS
SELECT
    id AS ID,
    Username AS Username,
    Password AS Password,
    Position AS AccessLevel,  -- Assuming "Position" represents the role/level
    FirstName AS FirstName,
    LastName AS LastName,
    Position AS Position,    -- Position as is
    Email AS Email,
    Dept AS Dept,
    u_status AS u_status,
    otp AS otp,
    is_password_reset AS isPasswordReset
FROM users;

-- Documents View
CREATE VIEW old_documents AS
SELECT
    d.id AS DocumentID,
    d.doc_no AS DocNo,
    d.title AS Title,
    d.description AS Description,
    d.author AS Author,
    d.date_published AS DatePublished,
    c.name AS Category,
    d.file_path AS FilePath,
    d.user_id AS UserID,
    d.d_status AS DStatus,
    d.isArchive AS IsArchive,  -- Corrected column name
    d.resolution_no AS ResolutionNo,
    d.ordinance_no AS OrdinanceNo,
    d.approval_timestamp AS ApprovalTimestamp
FROM documents d
JOIN categories c ON d.category_id = c.id;

-- Document Timeline View
CREATE VIEW old_document_timeline AS
SELECT
    id AS TimelineID,
    document_id AS DocumentID,
    action AS Action,
    changed_column AS ChangedColumn,
    old_value AS OldValue,
    new_value AS NewValue,
    performed_by AS PerformedBy,
    timestamp AS Timestamp,
    rejection_timestamp AS RejectionTimestamp,
    comment AS Comment
FROM document_timeline;
